import pyorc

file_path = "example.orc"
with open(file_path, "rb") as file:
    reader = pyorc.Reader(file)
    print("Schema:", reader.schema)
    print("Type:", type(reader.schema))
    for record in reader:
        print(record)
