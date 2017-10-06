cd `dirname $0`
for i in */check.sh; do
  echo -- check $i --
  eval $i
  RES=$?
  if [ $RES -ne 0 ]; then
    echo erreur
    exit 1
  fi
done
