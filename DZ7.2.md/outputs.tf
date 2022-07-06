output "yandex_account_id" {
  value = data.yandex_client_config.client.id
}

output "yandex_zone" {
  value = data.yandex_client_config.client.zone
}

output "yandex_yandex_iam_service_account_name" {
  value = data.yandex_iam_service_account.sa.name
}



output "internal_ip_address_node" {
  value = yandex_compute_instance.test-node.network_interface.0.ip_address
}

output "external_ip_address_node" {
  value = yandex_compute_instance.test-node.network_interface.0.nat_ip_address
}

output "subnet_id_node" {
  value = yandex_compute_instance.test-node.network_interface.0.subnet_id
}