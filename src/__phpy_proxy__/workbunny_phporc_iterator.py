import typing

class workbunny_phporc_iterator(typing.Iterator):
  def __init__(self, _this):
    self.__this = _this
    _this.set('_super', super())
    _this.set('_self', self)
    _this.call('__init')

  def __iter__(self, ):
    return self.__this.call('__iter__', )

  def __next__(self, ):
    res = self.__this.call('__next__', )
    if res == None:
      raise StopIteration
    return res

  def __init(self, ):
    return self.__this.call('__init', )

