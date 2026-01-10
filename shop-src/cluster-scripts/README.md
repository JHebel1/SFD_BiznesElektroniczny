# Creators

Adam Klamrowski (198199), Jakub Hebel (197719), Konrad Cichosz (197648), Marcel Kańduła (197677)

# Description

To run shop on cluster You have to be connected to WETI VPN

## Transfering files to cluster

```bash
scp -o ProxyJump=rsww@172.20.83.101 "path on your local pc" hdoop@student-swarm01.maas:/opt/storage/actina15-20/block-storage/students/projects/students-swarm-services/BE_197648/"name of file"
```

## Restoring the database on cluster

You have to be in folder /opt/storage/actina15-20/block-storage/students/projects/students-swarm-services/BE_197648 on cluster
```bash
./database_restore.sh
```

## Running shop on cluster

```bash
./build.sh
```

## Creating tunnel to cluster

```bash
ssh -L 19764:student-swarm01.maas:19764 rsww@172.20.83.101
```

## Checking if it works

- Prestashop: [https://localhost:19764](https://localhost:19764)

