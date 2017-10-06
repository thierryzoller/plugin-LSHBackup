PROG=0
echo $PROG > $1
cd `dirname $0`
NBSTEPS=`ls */install.sh | wc -l`
STEP=$((100/$NBSTEPS))
for i in */install.sh; do
  echo -- installation $i --
  PROG=$(($PROG+$STEP))
  eval $i
  echo $PROG > $1
done
rm $1
