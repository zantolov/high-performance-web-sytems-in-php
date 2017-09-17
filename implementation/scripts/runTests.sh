# $1 - Target URL
# $2 - Test name

if [ $# -eq 0 ]
  then
    echo "Missing arguments. Usage: URL testName";
    exit;
fi

mkdir -p tests/$2

docker run --rm -t yokogawa/siege -d0 -r1 -c1 $1 > tests/$2/siege_$2_c1.log 2>&1
sleep 20s
docker run --rm -t yokogawa/siege -d0 -t1M -c5 $1 > tests/$2/siege_$2_c_5.log 2>&1
sleep 20s
docker run --rm -t yokogawa/siege -d0 -t1M -c10 $1 > tests/$2/siege_$2_c_10.log 2>&1
sleep 20s
docker run --rm -t yokogawa/siege -d0 -t1M -c15 $1 > tests/$2/siege_$2_c_15.log 2>&1
sleep 20s
docker run --rm -t yokogawa/siege -d0 -t1M -c25 $1 > tests/$2/siege_$2_c_25.log 2>&1
sleep 20s
docker run --rm -t yokogawa/siege -d0 -t1M -c50 $1 > tests/$2/siege_$2_c_50.log 2>&1
sleep 20s
docker run --rm -t yokogawa/siege -d0 -t1M -c70 $1 > tests/$2/siege_$2_c_70.log 2>&1
sleep 50s
docker run --rm -t yokogawa/siege -d0 -t1M -c100 $1 > tests/$2/siege_$2_c_100.log 2>&1
sleep 50s
docker run --rm -t yokogawa/siege -d0 -t1M -c300 $1 > tests/$2/siege_$2_c_300.log 2>&1
sleep 50s
docker run --rm -t yokogawa/siege -d0 -t1M -c500 $1 > tests/$2/siege_$2_c_500.log 2>&1