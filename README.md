<p align="center"><img width="260px" src="https://chaz6chez.cn/images/workbunny-logo.png" alt="workbunny"></p>

**<p align="center">workbunny/php-orc</p>**

**<p align="center">🐇 PHP library for reading and writing Apache ORC file format. It uses the swoole/phpy extension to call the Python module PyORC for implementation. </p>**


# 简介

- 通过`swoole/PHPy`内联调用`python/pyorc`实现对`Apache ORC`格式文件的读写

# 安装

- 安装composer包

    ```shell
    composer require workbunny/php-orc
    ```
  
- 安装Python、pip、Python-dev
    > 自行安装 或 通过`.vendor/bin/php-orc install:python`安装, `--help`查看帮助

- 安装`PHPy`拓展
    > 自行安装 或 通过`.vendor/bin/php-orc install:phpy`安装, `--help`查看帮助

- 安装`pyorc`及依赖模块
    > 自行安装 或 通过`.vendor/bin/php-orc install:pyorc`安装, `--help`查看帮助

# 使用

## 直接调用

- 实例化`Reader`类进行读取操作
- 实例化`Writer`类进行写入操作

**详见 [examples目录](https://github.com/workbunny/php-orc/tree/main/examples)**

## 继承开发 & 使用

- 继承`ReaderClass`类进行读取操作的拓展开发或使用
- 继承`WriterClass`类进行写入操作的拓展开发或使用

**详见 [examples目录](https://github.com/workbunny/php-orc/tree/main/examples)**

# 说明

- 关于`Apache ORC`格式可以参考[Apache ORC](https://orc.apache.org/docs/)
- 关于`Python/PyORC`可以参考[Python/PyORC](https://github.com/apache/orc/tree/master/python)
  - 建议使用`--venv`虚拟环境安装，在当前项目根目录中会创建`.venv`目录存放源码
- 关于使用上的代码问题，可以参考`swoole/PHPy`的文档[PHPy](https://github.com/swoole/phpy/blob/main/docs/cn/README.md)
- 使用本组件基于`Class`进行深度开发需要对`Python`及`PyORC`有一定了解

# 其他格式

- `Apache Parquet`
  1. https://github.com/flow-php/parquet
  2. https://github.com/codename-hub/php-parquet
- `Apache Iceberg`
  - 暂无，后续`workbunny`会推出
