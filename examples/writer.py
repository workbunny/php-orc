import pyorc

# 定义架构
schema = pyorc.TypeDescription.from_string("struct<id:int,group:string,name:string,email:string>")

file_path = "example-py.orc"
with open(file_path, "wb") as orc_file:
    writer = pyorc.Writer(orc_file, schema)

    # 写入数据
    writer.write((1, "workbunny", "test1", "test1@workbunny.com"))
    writer.write((2, "workbunny", "test2", "test2@workbunny.com"))
    writer.write((3, "workbunny", "test3", "test3@workbunny.com"))
    writer.write((4, "workbunny", "test4", "test4@workbunny.com"))
    writer.write((5, "workbunny", "test5", "test5@workbunny.com"))

    writer.close()

print("Schema:", writer.schema)