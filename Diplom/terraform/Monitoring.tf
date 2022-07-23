resource "yandex_compute_instance" "monitoring" {
  name     = "monitoring"
  hostname = "monitoring.${var.name_domain}"

  resources {
    cores  = 4
    memory = 4
  }

  boot_disk {
    initialize_params {
      image_id = "fd8fte6bebi857ortlja"   # ubuntu-20-04-lts-v20211227
    }
  }

  network_interface {
    subnet_id = yandex_vpc_subnet.subnet-1.id
  }

  metadata = {
    user-data = "${file("meta.txt")}"
  }
}
