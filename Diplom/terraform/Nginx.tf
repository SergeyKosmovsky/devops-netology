resource "yandex_compute_instance" "nginx"  {
  name     = "nginx"
  hostname = var.name_domain

  resources {
    cores  = 2
    memory = 2
  }

  boot_disk {
    initialize_params {
      image_id = "fd8fte6bebi857ortlja"   # ubuntu-20-04-lts-v20211227
      size     = 5
    }
  }

  network_interface {
    subnet_id      = yandex_vpc_subnet.subnet-1.id
    nat            = true
    nat_ip_address = var.yc_reserved_ip
  }

  metadata = {
    user-data = "${file("meta.txt")}"
    ssh-keys  = "ubuntu:${file("~/.ssh/id_rsa.pub")}"
  }
}
