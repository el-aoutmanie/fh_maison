#!/bin/bash

###############################################################################
# FH Maison - Security Hardening Script
# Implements security best practices for production server
###############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}FH Maison - Security Hardening${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root (use sudo)${NC}"
    exit 1
fi

echo -e "${YELLOW}[1/8] Configuring Fail2Ban...${NC}"
cat > /etc/fail2ban/jail.local <<EOF
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5
destemail = admin@fhmaison.fr
sendername = Fail2Ban

[sshd]
enabled = true
port = ssh
logpath = %(sshd_log)s
backend = %(sshd_backend)s

[nginx-http-auth]
enabled = true
port = http,https
logpath = /var/log/nginx/error.log

[nginx-limit-req]
enabled = true
port = http,https
logpath = /var/log/nginx/error.log

[nginx-botsearch]
enabled = true
port = http,https
logpath = /var/log/nginx/access.log
maxretry = 2
EOF

systemctl enable fail2ban
systemctl restart fail2ban

echo -e "${YELLOW}[2/8] Securing SSH configuration...${NC}"
# Backup original config
cp /etc/ssh/sshd_config /etc/ssh/sshd_config.backup

# Apply security settings
sed -i 's/#PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config
sed -i 's/#PasswordAuthentication yes/PasswordAuthentication no/' /etc/ssh/sshd_config
sed -i 's/#PubkeyAuthentication yes/PubkeyAuthentication yes/' /etc/ssh/sshd_config
sed -i 's/X11Forwarding yes/X11Forwarding no/' /etc/ssh/sshd_config

# Add additional security settings
cat >> /etc/ssh/sshd_config <<EOF

# Additional security settings
Protocol 2
MaxAuthTries 3
MaxSessions 2
ClientAliveInterval 300
ClientAliveCountMax 2
AllowUsers fhmaison
EOF

systemctl restart sshd

echo -e "${YELLOW}[3/8] Configuring automatic security updates...${NC}"
apt install -y unattended-upgrades
dpkg-reconfigure -plow unattended-upgrades

cat > /etc/apt/apt.conf.d/50unattended-upgrades <<EOF
Unattended-Upgrade::Allowed-Origins {
    "\${distro_id}:\${distro_codename}";
    "\${distro_id}:\${distro_codename}-security";
    "\${distro_id}ESMApps:\${distro_codename}-apps-security";
    "\${distro_id}ESM:\${distro_codename}-infra-security";
};
Unattended-Upgrade::AutoFixInterruptedDpkg "true";
Unattended-Upgrade::MinimalSteps "true";
Unattended-Upgrade::Remove-Unused-Kernel-Packages "true";
Unattended-Upgrade::Remove-Unused-Dependencies "true";
Unattended-Upgrade::Automatic-Reboot "false";
EOF

echo -e "${YELLOW}[4/8] Securing MySQL...${NC}"
# Disable remote access
sed -i "s/bind-address.*/bind-address = 127.0.0.1/" /etc/mysql/mysql.conf.d/mysqld.cnf
systemctl restart mysql

echo -e "${YELLOW}[5/8] Hardening kernel parameters...${NC}"
cat > /etc/sysctl.d/99-security.conf <<EOF
# IP Forwarding
net.ipv4.ip_forward = 0

# Disable source packet routing
net.ipv4.conf.all.accept_source_route = 0
net.ipv6.conf.all.accept_source_route = 0

# Ignore ICMP redirects
net.ipv4.conf.all.accept_redirects = 0
net.ipv6.conf.all.accept_redirects = 0

# Ignore send redirects
net.ipv4.conf.all.send_redirects = 0

# Disable IP source routing
net.ipv4.conf.all.accept_source_route = 0

# Log Martians
net.ipv4.conf.all.log_martians = 1

# Ignore ICMP ping requests
net.ipv4.icmp_echo_ignore_all = 0

# Ignore Broadcast Requests
net.ipv4.icmp_echo_ignore_broadcasts = 1

# Enable TCP SYN Cookie Protection
net.ipv4.tcp_syncookies = 1

# Increase TCP backlog
net.ipv4.tcp_max_syn_backlog = 2048

# Enable ExecShield
kernel.exec-shield = 1
kernel.randomize_va_space = 2
EOF

sysctl -p /etc/sysctl.d/99-security.conf

echo -e "${YELLOW}[6/8] Setting up log rotation...${NC}"
cat > /etc/logrotate.d/fhmaison <<EOF
/var/www/fhmaison/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 fhmaison www-data
    sharedscripts
    postrotate
        php /var/www/fhmaison/artisan cache:clear > /dev/null 2>&1 || true
    endscript
}
EOF

echo -e "${YELLOW}[7/8] Installing and configuring ModSecurity...${NC}"
apt install -y libapache2-mod-security2 -qq || echo "ModSecurity not available for Nginx, skipping"

echo -e "${YELLOW}[8/8] Creating security monitoring script...${NC}"
cat > /usr/local/bin/fhmaison-security-check.sh <<'EOF'
#!/bin/bash
# FH Maison Security Check Script

echo "=== FH Maison Security Status ==="
echo ""
echo "Failed SSH Login Attempts (Last 24h):"
grep "Failed password" /var/log/auth.log | grep "$(date +%b\ %e)" | wc -l
echo ""
echo "Fail2Ban Status:"
fail2ban-client status
echo ""
echo "Firewall Status:"
ufw status
echo ""
echo "Open Ports:"
ss -tulpn | grep LISTEN
echo ""
echo "Last Logins:"
last -n 5
echo ""
echo "Disk Usage:"
df -h | grep -E '^/dev/'
echo ""
echo "Memory Usage:"
free -h
echo ""
echo "System Load:"
uptime
EOF

chmod +x /usr/local/bin/fhmaison-security-check.sh

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Security hardening completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Security measures applied:${NC}"
echo "✓ Fail2Ban configured and running"
echo "✓ SSH hardened (root login disabled, password auth disabled)"
echo "✓ Automatic security updates enabled"
echo "✓ MySQL secured (local access only)"
echo "✓ Kernel parameters hardened"
echo "✓ Log rotation configured"
echo "✓ Security monitoring script installed"
echo ""
echo -e "${YELLOW}Important reminders:${NC}"
echo "1. Ensure SSH key-based authentication is set up before logging out"
echo "2. Test SSH access from a new terminal before closing current session"
echo "3. Run security check: /usr/local/bin/fhmaison-security-check.sh"
echo "4. Monitor logs regularly: tail -f /var/log/fail2ban.log"
echo ""
echo -e "${RED}WARNING: Root login and password authentication are now disabled!${NC}"
