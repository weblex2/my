#!/bin/sh
wget -qO- https://raw.githubusercontent.com/creationix/nvm/v0.32.0/install.sh | bash
export NVM_DIR="/mnt/web418/a2/92/52085492/htdocs/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"
nvm install 16.0.0