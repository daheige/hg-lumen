#!/bin/sh
root_dir=$(cd "$(dirname "$0")"; cd ..; pwd)
#初始化目录权限
mkdir -p $root_dir/storage/{app,framework,logs}/
chmod 777 -R $root_dir/storage

#初始化.env
# rsync $root_dir/.env.example $root_dir/.env
