if [ `dpkg -l | grep smbclient | wc -l` -ne 2 ]; then
  exit 1
fi

