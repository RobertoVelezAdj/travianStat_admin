import scrapy
#scrapy crawl players
class PlayersSpider(scrapy.Spider):
    name = "players"
    start_urls = ["https://www.atptour.com/es/rankings/singles?rankRange=1-3999"]#4001
    def parse(self, response):
        players = response.xpath('//table[@id="player-rank-detail-ajax"]/tbody/tr') 
        for player in players:
            posicion='';

            posicion = player.xpath('.//td[@class="rank-cell border-left-4 border-right-dash-1"]/text()').get()
            nombre = player.xpath('.//span[@class="player-cell-wrapper"]/a/text()').get()
            edad = player.xpath('.//td[@class="age-cell border-left-dash-1 border-right-4"]/text()').get()
            puntos = player.xpath('.//td[@class="points-cell border-right-dash-1"]/a/text()').get()
            aux = player.xpath('.//div[@class="country-item"]').get()
            pais = aux.split('="')[3].split('"')[0]
            enlace =  player.xpath('.//td[@class="player-cell border-left-dash-1 border-right-dash-1"]/span/a/@href').get()
            id = enlace.split('/')[4]
            yield response.follow(enlace, callback=self.parse_player_info,cb_kwargs=dict(nombre=nombre, posicion=posicion, edad=edad, puntos=puntos, pais=pais,id=id,enlace=enlace))
           
    def parse_player_info(self, response, nombre, posicion, edad, puntos, pais,id,enlace):
        
        fecha_nacimiento = response.xpath('//span[@class="table-birthday"]/text()').get()
        peso = response.xpath('//span[@class="table-weight-kg-wrapper"]/text()').get()
        height = response.xpath('//span[@class="table-height-cm-wrapper"]/text()').get()
        lugar_nacimiento  = response.xpath('//div[contains(text(), "Lugar de Nacimiento")]/following-sibling::div/text()').get()
        playing_hand = response.xpath('//div[contains(text(), "Juego")]/following-sibling::div/text()').get()

        yield {
            'posicion': posicion.replace("\r\n","").strip(),
            'nombre':  nombre.replace("\r\n","").strip(),
            'edad':  edad.replace("\r\n","").strip(),
            'puntos':  puntos.replace("\r\n","").strip().replace(",",""),
            'pais':  pais.replace("\r\n","").strip(),
            'enlace':enlace.replace("\r\n","").strip(),
            'id': id.replace("\r\n","").strip(),
            'altura':height.replace("\r\n","").strip().replace("(","").replace(")",""),
            'fecha_nacimiento':fecha_nacimiento.replace("\r\n","").strip().replace("(","").replace(")",""),
            'peso': peso.replace("\r\n","").strip().replace("(","").replace(")",""),
            'lugar_nacimiento': lugar_nacimiento.replace("\r\n","").strip().replace("(","").replace(")",""),
            'derecha':playing_hand.replace("\r\n","").strip().replace("(","").replace(")","").split(',')[0],
            'reves':playing_hand.replace("\r\n","").strip().replace("(","").replace(")","").split(',')[1].replace("Reves","").strip()
         }