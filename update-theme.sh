#!/bin/bash
# =============================================
# Script cập nhật theme VietFarmy - Không cần password
# =============================================

echo "============================================="
echo "  VIETFARMY - Cập nhật Theme Gema"
echo "============================================="
echo ""

HOST="185.187.241.39"
PORT="65002"
USER="u200682234"
THEME_PATH="domains/vietfarmy.vn/public_html/wp-content/themes/gema"

# Kiểm tra kết nối
echo "🔄 Đang kiểm tra kết nối..."

# Git pull
echo "📥 Đang pull code mới nhất..."
ssh -p $PORT $USER@$HOST "cd $THEME_PATH && git pull"

echo ""
echo "============================================="
echo "  ✅ Hoàn tất!"
echo "============================================="
