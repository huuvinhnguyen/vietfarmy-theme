#!/usr/bin/expect -f
# =============================================
# Script thêm SSH Key vào Server VietFarmy
# =============================================

set timeout 30
set password "Huuvinh@12"
set ssh_key "ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIFwJWNtK/NWZA/Rv5GlX44vXA0QZ9f919QPe63bBwz8L ssh key"

# Kết nối SSH
spawn ssh -p 65002 -o StrictHostKeyChecking=no u200682234@185.187.241.39 "mkdir -p ~/.ssh && chmod 700 ~/.ssh && echo '$ssh_key' >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys && echo 'SSH Key added successfully'"

# Đợi password prompt
expect {
    "password:" {
        send "$password\r"
        exp_continue
    }
    "already exist" {
        exp_continue
    }
    eof
}
