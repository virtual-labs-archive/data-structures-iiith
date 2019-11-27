# for entry in "."/*".html"
# do
# 	echo $entry
#   "./gtmPatcher.py" $entry
# done
for entry in "../src/lab"/*
do
  if [ -d "${entry}" ] ; then
    for another_file in $entry/*".html"
    do
  	"./gtm-patcher.py" $another_file
    done  
  fi
done 
