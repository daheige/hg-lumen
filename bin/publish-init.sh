#!/usr/bin/env bash
### 运维发布上线，需执行如下脚本

root_dir=$(cd "$(dirname "$0")"; cd ..; pwd)

echo "Initilize application: $root_dir"

# change dir mode
chmod -R 777 $root_dir/storage
chmod +x $root_dir/bin/*.sh
# chown -R www. $root_dir/storage

#根据环境复制env file
EnvConf=$root_dir/_env.testing
if [ -f /etc/php.env.production ]; then
    EnvConf=$root_dir/_env.production
elif [ -f /etc/php.env.testing ]; then
    EnvConf=$root_dir/_env.testing
elif [ -f /etc/php.env.staging ]; then
    EnvConf=$root_dir/_env.staging
fi

echo 'copy env file '$EnvConf' to .env'
rsync -u $EnvConf $root_dir/.env
