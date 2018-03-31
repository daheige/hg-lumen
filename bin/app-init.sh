#!/usr/bin/env bash
#初始化目录权限
root_dir=$(cd "$(dirname "$0")"; cd ..; pwd)

mkdir -p $root_dir/storage/app
mkdir -p $root_dir/storage/framework
mkdir -p $root_dir/storage/logs
chmod 777 -R $root_dir/storage
