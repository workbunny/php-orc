# php-orc
🐇 PHP library for reading and writing Apache ORC file format. It uses the swoole/phpy extension to call the Python module PyORC for implementation.

# 简介

- 通过`swoole/PHPy`内联调用`python/pyorc`实现对ORC格式文件的读写

# 安装

- 安装composer包

    ```shell
    composer require workbunny/php-orc
    ```
  
- 安装Python
    > 自行安装 或 通过`.vendor/bin/php-orc install:python`安装, `--help`查看帮助

- 安装`PHPy`拓展
    > 自行安装 或 通过`.vendor/bin/php-orc install:phpy`安装, `--help`查看帮助

- 安装`pyorc`模块
    > 自行安装 或 通过`.vendor/bin/php-orc install:pyorc`安装, `--help`查看帮助

# 使用

- 详见[examples目录](examples)
