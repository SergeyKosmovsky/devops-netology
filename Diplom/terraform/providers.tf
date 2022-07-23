terraform {
  required_providers {
    yandex = {
      source = "yandex-cloud/yandex"
    }
  }
  required_version = ">= 0.13"

  backend "s3" {
    endpoint   = "storage.yandexcloud.net"
    bucket     = "bak-kos"
    region     = "ru-central1"
    key        = "stage/bak-kos-stage.tfstate"
    access_key = "YCAJERjpPSCB317vg1MlaR3Dm"                  # удалить
    secret_key = "YCOipEU69D68B0uoNs9ZDbJAa1z-L93qp3D_w1OP"   # удалить


    skip_region_validation      = true
    skip_credentials_validation = true
  }
}

provider "yandex" {
  token     = var.token
  cloud_id  = var.cloud_id
  folder_id = var.folder_id
  zone      = "ru-central1-a"
}
