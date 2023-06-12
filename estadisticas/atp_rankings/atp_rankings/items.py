# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy


class AtpRankingsItem(scrapy.Item):
    posicion = scrapy.Field()
    nombre = scrapy.Field()
    edad = scrapy.Field()
    puntos = scrapy.Field()
    pais = scrapy.Field()