# $1 - Target URL
# $2 - Test name

if [ $# -eq 0 ]
  then
    echo "Missing arguments. Usage: URL testName";
    exit;
fi

mkdir -p tests/$2

docker run --rm -t yokogawa/siege -r1 -c1 $1 > tests/$2/$2_single_request.log
docker run --rm -t yokogawa/siege -t2m -c5 $1 > tests/$2/$2_c_5.log
docker run --rm -t yokogawa/siege -t2m -c7 $1 > tests/$2/$2_c_7.log
docker run --rm -t yokogawa/siege -t2m -c10 $1 > tests/$2/$2_c_10.log
docker run --rm -t yokogawa/siege -t2m -c12 $1 > tests/$2/$2_c_12.log
docker run --rm -t yokogawa/siege -t2m -c15 $1 > tests/$2/$2_c_15.log


