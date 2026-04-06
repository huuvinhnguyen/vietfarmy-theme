#!/bin/bash
# =============================================
# Script: Lưu code + Commit + Push + Deploy
# =============================================

# Màu sắc
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "============================================="
echo "  💾 VIETFARMY - Save, Commit & Deploy"
echo "============================================="
echo ""

# Kiểm tra thư mục git
if [ ! -d ".git" ]; then
    echo -e "${RED}❌ Lỗi: Không tìm thấy .git repository${NC}"
    exit 1
fi

# Kiểm tra có thay đổi không
if git diff --quiet && git diff --cached --quiet; then
    echo -e "${YELLOW}⚠️  Không có thay đổi để commit${NC}"
else
    # Hiển thị các thay đổi
    echo "📝 Các file đã thay đổi:"
    git status --short
    echo ""

    # Commit message (có thể tùy chỉnh)
    COMMIT_MSG="${1:-Update theme - $(date '+%Y-%m-%d %H:%M:%S')}"

    echo -e "${GREEN}📌 Commit message: ${COMMIT_MSG}${NC}"
    echo ""

    # Git Add
    echo "🔄 Đang git add..."
    git add -A

    # Git Commit
    echo "💾 Đang commit..."
    git commit -m "$COMMIT_MSG"

    # Git Push
    echo "📤 Đang push lên GitHub..."
    git push

    echo ""
    echo "✅ Đã push lên GitHub!"
fi

# Deploy lên Server
echo ""
echo "============================================="
echo "  🚀 Deploy lên Server"
echo "============================================="

HOST="185.187.241.39"
PORT="65002"
USER="u200682234"
THEME_PATH="domains/vietfarmy.vn/public_html/wp-content/themes/gema"

echo "🔄 Đang kết nối server..."
ssh -p $PORT $USER@$HOST "cd $THEME_PATH && git pull"

echo ""
echo "============================================="
echo "  🎉 Hoàn tất! Website đã được cập nhật!"
echo "============================================="
