import pyorc

# 定义架构
schema = pyorc.TypeDescription.from_string("struct<name:string,age:int>")

file_path = "example.orc"
with open(file_path, "wb") as orc_file:
    writer = pyorc.Writer(orc_file, schema)

    # 写入数据
    writer.write(("Alice", 30))
    writer.write(("Bob", 25))
    writer.write(("Charlie", 35))

    writer.close()

print("ORC 文件已创建: example.orc")
