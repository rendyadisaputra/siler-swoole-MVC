echo $1
for i in {1..10}
do
  echo $1;
  ab -n 1000 -c 100 $1
done
