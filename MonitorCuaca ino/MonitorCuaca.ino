#include <ESP8266WiFi.h>
#include <MQTT.h>
#include <dht11.h>
#include <TinyGPS++.h> 
#include <SoftwareSerial.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <SPI.h>
#include <MFRC522.h>

const int idWilayah = 2;
const char ssid[] = "Xze";
const char pass[] = "qwerty98";
char server[] = "192.168.43.125"; 

TinyGPSPlus gps;  
SoftwareSerial ss(12, 13); // The serial connection to the GPS device
WiFiClient net;

#define DHT11PIN D5
#define windPin D2 
int OutputAO=A0;
dht11 DHT11;

// VARIABEL SENSOR GPS,HUJAN,SUHU //
float humidityData, temperatureData, rainData;
float latitude , longitude;
String lat_str,lng_str;

// VARIABEL SENSOR ANEMOMETER // 
const float pi = 3.14159265; // pi number
int period = 10000; // Measurement period (miliseconds)
int delaytime = 10000; // Time between samples (miliseconds)
int radio = 80; // Distance from center windmill to outer cup (mm)
int jml_celah = 18; // jumlah celah sensor
unsigned int counter = 0; // B/W counter for sensor
unsigned int RPM = 0; // Revolutions per minute
float speedwind = 0; // Wind speed (m/s)

// VARIABEL DELAY MENIT //
const int Minutes = 1;

void ICACHE_RAM_ATTR addcount()
{
counter++;
}

void setup() {
  Serial.begin(115200);
  ss.begin(9600);
  WiFi.begin(ssid, pass);

  pinMode (OutputAO, INPUT) ;
  pinMode(D2, INPUT);
  digitalWrite(D2, HIGH);
}

void loop() {
  while (ss.available() > 0) //while data is available
      if (gps.encode(ss.read())) //read gps data
      {
        if (gps.location.isValid()) //check whether gps location is valid
        {
          latitude = gps.location.lat();
          longitude = gps.location.lng();
          lat_str = String(latitude , 6);
          lng_str = String(longitude , 6);
          
          int chk = DHT11.read(DHT11PIN);
          rainData = analogRead(OutputAO);
          humidityData = (float)DHT11.humidity;
          temperatureData = (float)DHT11.temperature; 

          Serial.println(lat_str);
          Serial.println(lng_str);

          Serial.println("Start");
          windvelocity();
          Serial.println("finished");
          Serial.print("Counter:");
          Serial.print(counter);
          Serial.print("RPM:");
          RPMcalc();
          Serial.print(RPM);
          Serial.print("Wind speed:");
          Windcalc();
          Serial.print(speedwind);
          Serial.print("[m/s]");
          Serial.println();
          
          Sending_To_phpmyadmindatabase();
          Serial.println("SEND");
          delay(5000);
          //delay(1000*60*Minutes);
        }
      }
}

void windvelocity()
{
  speedwind = 0;
  counter = 0;
  attachInterrupt(digitalPinToInterrupt(windPin), addcount, CHANGE);
  unsigned long startTime = millis();
  while(millis() < startTime + period) {yield();}
  detachInterrupt(1);
}

void RPMcalc()
{
  RPM=((counter/jml_celah)*60)/(period/1000); // Calculate revolutions per minute (RPM)
}

void Windcalc(){
  speedwind = ((2 * pi * radio * RPM)/60) / 1000; // Calculate wind speed on m/s
}

 void Sending_To_phpmyadmindatabase()   //CONNECTING WITH MYSQL
 {
   if (net.connect(server, 80)) {
          Serial.println("connected");
          // Make a HTTP request:
          Serial.print("GET /WebSkripsi/StoreDB.php?humidity=");
          net.print("GET /WebSkripsi/StoreDB.php?humidity=");     
          Serial.println(humidityData);
          net.print(humidityData);
          
          net.print("&temperature=");
          Serial.println("&temperature=");
          net.print(temperatureData);
          Serial.println(temperatureData);
          
          net.print("&rain=");
          Serial.println("&rain=");
          net.print(rainData);
          Serial.println(rainData);
      
          net.print("&latitude=");
          Serial.println("&latitude=");
          net.print(lat_str);
          Serial.println(lat_str);
      
          net.print("&longitude=");
          Serial.println("&longitude=");
          net.print(lng_str);
          Serial.println(lng_str);

          net.print("&speedwind=");
          Serial.println("&speedwind=");
          net.print(speedwind);
          Serial.println(speedwind);
      
          net.print("&idWilayah=");
          Serial.println("&idWilayah=");
          net.print(idWilayah);
          Serial.println(idWilayah);
          
          net.print(" ");      //SPACE BEFORE HTTP/1.1
          net.print("HTTP/1.1");
          net.println();
          net.println("Host: 192.168.43.125");
          net.println("Connection: close");
          net.println();
        } else {
          // if you didn't get a connection to the server:
          Serial.println("connection failed");
        }
 }
