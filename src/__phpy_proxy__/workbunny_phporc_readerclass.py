import pyorc

class workbunny_phporc_readerclass(pyorc.Reader):
  def __init__(self, _this):
    self.__this = _this
    _this.set('_super', super())
    _this.set('_self', self)
    _this.call('__init')

  def __init(self, ):
    return self.__this.call('__init', )

  def parent(self, ):
    return self.__this.call('parent', )

  def count(self, ):
    return self.__this.call('count', )

