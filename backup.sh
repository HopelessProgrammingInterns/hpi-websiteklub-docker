#!/bin/bash

MOUNT_DIR=/mnt/docker-backup
BACKUP_DIR=$1

for volume in $(docker volume ls -q); do
	# skip the volumes that just have that id, no name
	if [ $(wc -m <<< "$volume") = "65" ]; then
		continue
	fi
	MOUNTPOINT=$(docker volume inspect --format="{{ .Mountpoint }}" "$volume")
	MOUNT_DESTINATION="${MOUNT_DIR}/${volume}"
	BACKUP_DESTINATION="${BACKUP_DIR}/${volume}"
	mkdir -p "$MOUNT_DESTINATION"
	mkdir -p "$BACKUP_DESTINATION"
	echo "backing up $volume to $BACKUP_DESTINATION"
	mount --bind "$MOUNTPOINT" "$MOUNT_DESTINATION"
	#rsync -azR --delete -e "/usr/bin/ssh -i /root/.ssh/rsyncbackup_id_rsa -o \"PasswordAuthentication no\"" "/.${MOUNT_DESTINATION}" rsyncbackup@172.16.64.119:/srv/backup/hpi_backup/docker/ || (/root/umount-volumes.sh "$MOUNT_DESTINATION"; exit 1)
	cp -R "${MOUNT_DESTINATION}/*" "${BACKUP_DESTINATION}/"

	umount "$MOUNT_DESTINATION"
	rmdir "$MOUNT_DESTINATION"
done

