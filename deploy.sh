#!/bin/bash
# Használat:
#   ./deploy.sh          → feltölt a tesztre (aa könyvtár/teszt)
#   ./deploy.sh eles     → feltölt az éles oldalra (/AA)

TARGET="${1:-teszt}"
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PASS_FILE="$HOME/Git/KEM_REKUSOK/.ftp_pass"

if [ ! -f "$PASS_FILE" ]; then
    echo "Hiba: .ftp_pass fájl nem található! ($PASS_FILE)"
    exit 1
fi

FTP_HOST="ftp.nethely.hu"
FTP_USER="vilikiraly"
FTP_PASS="$(cat "$PASS_FILE" | tr -d '\n')"

case "$TARGET" in
    teszt)
        REMOTE_DIR="/AA/teszt"
        ;;
    eles)
        REMOTE_DIR="/AA"
        ;;
    *)
        echo "Használat: $0 [teszt|eles]"
        exit 1
        ;;
esac

echo "▶ Feltöltés: $SCRIPT_DIR → $FTP_HOST$REMOTE_DIR"
echo ""

TMPSCRIPT=$(mktemp /tmp/lftp_deploy.XXXXXX)
trap "rm -f '$TMPSCRIPT'" EXIT

cat > "$TMPSCRIPT" << EOF
open -u "$FTP_USER","$FTP_PASS" $FTP_HOST
set ftp:passive-mode on
set ssl:verify-certificate no
mirror --reverse --delete --verbose \
    --exclude-glob deploy.sh \
    --exclude-glob README.md \
    --exclude .claude/ \
    --exclude .git/ \
    $SCRIPT_DIR/ $REMOTE_DIR/
quit
EOF

lftp -f "$TMPSCRIPT"

echo ""
echo "✓ Kész!"
