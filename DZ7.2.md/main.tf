provider "yandex" {
  token     = var.yc_token
  cloud_id  = var.yandex_cloud_id
  folder_id = var.yandex_folder_id
  zone      = "ru-central1-a"
}

resource "yandex_vpc_network" "default" {
}

resource "yandex_vpc_subnet" "default" {
  name           = "subnet"
  zone           = "ru-central1-a"
  network_id     = yandex_vpc_network.default.id
  v4_cidr_blocks = ["192.168.101.0/24"]
}

resource "yandex_compute_instance" "test-node" {
  name                      = "test-node"
  zone                      = "ru-central1-a"
  hostname                  = "test-node.netology.yc"
  allow_stopping_for_update = true

  resources {
    cores  = 2
    memory = 2
  }

  boot_disk {
    initialize_params {
      image_id = "fd8fjtn3mj2kfe7h6f0r" # ubuntu-1804-lts
      type     = "network-nvme"
      size     = "10"
    }
  }

  network_interface {
    subnet_id = yandex_vpc_subnet.default.id
    nat       = true
  }

  metadata = {
    ssh-keys = "ubuntu:${file("id_rsa.pub")}"
  }
}

data "yandex_client_config" "client" {}

data "yandex_iam_service_account" "sa" {
  service_account_id = "ajenr7pu8oi5gj1o4q4h"
}
