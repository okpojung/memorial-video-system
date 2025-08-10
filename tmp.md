////mqtt

nohup python -u door_mqtt.py &

mosquitto_sub -t restaurant/door/getmyid &

//open 뒤에 숫자는 사용자 인덱스번호
mosquitto_pub -h 3.86.7.129 -t restaurant/door/open/1 -m 'open'

mosquitto_sub -t restaurant/door/openrlt


yum -y install python-pip
sudo pip3 install paho-mqtt
sudo pip3 install PyMySQL


//파이썬으로 에러나면 파이썬3로 실행하자


////mqtt

nohup python3 -u mqtt.py &

mosquitto_sub -d -h 192.168.94.41 -t baro/door/openrlt

mosquitto_pub -h 192.168.94.41 -t baro/door/open/050716634957 -m '01037635613'

mosquitto_sub -d -h 192.168.94.41 -t baro/door/getmyid

mosquitto_sub -d -h 192.168.94.41 -t baro/door/getdate

cors 오류 해결
https://medium.com/sjk5766/laravel-cors-%ED%97%88%EC%9A%A9-b2ae44589fc0
