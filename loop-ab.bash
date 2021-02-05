for i in {1..5}
do
  ab -n 1000 -c 100 http://192.168.56.66:9501/
done
