#!/usr/bin/env python
from urllib.request import urlopen
import urllib
import ssl
import mysql.connector
context = ssl._create_unverified_context()
conexion1=mysql.connector.connect(host="localhost", user=" ", passwd="P$ ", database="   ")
conexion2=mysql.connector.connect(host="localhost", user=" ", passwd="P$ ", database=" ")
cursor1=conexion1.cursor()
cursor1.execute("select ruta_inac,nombre,id from servidor")
informacion = list()
contador1 = 0
for base in cursor1:
    id_server=base[2]
    url = base[0]
    print(base[0])
    nombre_fichero="map"+ base[1]+".sql"
    nombre_fichero =nombre_fichero.replace(' ','_')
    contador1 = 1
    r = urlopen(url, context=context)
    with open(nombre_fichero, "wb") as f:
        f.write(r.read())
    r.close()
    # Nombre con el que quiero descargar el archivo.
    f = open(nombre_fichero, "r",errors="replace")
    contador1 = 2
    while(True):
        linea = f.readline()
        linea = linea.replace("'",'')
        linea = str(linea).replace("'",'')
        if not linea:
            break
        try:
            informacion=linea.split(',')
            coord_x=informacion[1]
            coord_y=informacion[2]
            id_raza=informacion[3]
            id_aldea=informacion[4]
            nombre_aldea=informacion[5].replace(';','')
            id_cuenta=informacion[6]
            nombre_cuenta=informacion[7].replace(';','')
            id_alianza=informacion[8]
            nombre_alianza=informacion[9].replace(';','')
            poblacion=informacion[10]
            #ALIANZAS
            cursor2=conexion2.cursor()
            contador1 = 3
            #print(id_alianza)
            strQuery="select count(*) from alianza_inac where idAlianza="+id_alianza+" and id_server ="+str(id_server)
            cursor2.execute(strQuery)
            
            for base3 in cursor2:
                contador1 = 5
                contador=base3[0]
                if contador==0:
                    strQuery="Insert into alianza_inac (id_server,idAlianza,NombreAlianza,created_at,updated_at) VALUES ("+str(id_server)+","+id_alianza+",'"+ nombre_alianza +"',CURDATE(),CURDATE())"
                    #print(strQuery)
                    cursor2.execute(strQuery)
                    contador1=6
                else:
                    strQuery="UPDATE alianza_inac SET NombreAlianza='"+nombre_alianza+"',updated_at=CURRENT_DATE(), id_server ="+str(id_server)+"  WHERE idAlianza = "+id_alianza +" and id_server ="+str(id_server)
                    cursor2.execute(strQuery)

            #meter else por si modifica el nombre de la alianza      
            #CUENTAS
            #print(id_cuenta)
            strQuery="select count(*) from cuenta_inac where idCuenta="+id_cuenta+ " and idServer = "+str(id_server)
            cursor2.execute(strQuery)
            for base2 in cursor2:
                contador=base2[0]
            if contador==0:
                #Insertar nueva alianza 
                    strQuery="INSERT INTO cuenta_inac(IdCuenta,IdServer,IdAlianza,NombreCuenta,Raza,Activo,created_at,supend_at, modif_at) VALUES ("+str(id_cuenta)+","+str(id_server)+","+str(id_alianza)+",'"+ nombre_cuenta+"',"+str(id_raza)+",'1',CURDATE(),NULL,CURDATE())"
                    #print(strQuery)
                    cursor2.execute(strQuery)
            else:
                    strQuery="UPDATE cuenta_inac SET modif_at=CURRENT_DATE(),IdAlianza="+str(id_alianza)+",NombreCuenta='"+ nombre_cuenta+"'  WHERE idCuenta = "+id_cuenta +" and idServer ="+str(id_server)
                    cursor2.execute(strQuery)       
            #ALDEAS  
                #Insertar nueva ALDEA 
            strQuery="INSERT INTO aldea_inac(id_server,IdAldea,NombreAldea,coord_x,coord_y,poblacion,created_at,updated_at,IdCuenta) VALUES ("+str(id_server)+","+id_aldea+",'"+nombre_aldea+"',"+coord_x+","+coord_y+","+poblacion+",CURDATE(),CURDATE(),"+str(id_cuenta)+")"
            #print(strQuery)
            cursor2.execute(strQuery)
        except: 

           print(contador1)
           #contador1 = 3
    f.close()
    strQuery="UPDATE servidor SET fch_mod=CURDATE() WHERE id ="+str(id_server)
    cursor2.execute(strQuery)

conexion1.commit()
conexion2.commit()    
conexion1.close() 
conexion2.close() 
