#!/bin/bash
# Made to work along with statebeta.sh

if [ $# = 0 ]
then
echo Usage : $0 *data*
else
screen -S arduino -X eval "stuff $1"
echo screen -S arduino -X eval "stuff $1"
fi