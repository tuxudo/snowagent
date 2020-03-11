Snowagent Module
==============

Reports back on how the Snow Software agent is configured.


Table Schema
---
* version - VARCHAR(255) - Version of snow agent
* version_long - VARCHAR(255) - Version string of snow agent
* build - VARCHAR(255) - Build of snow agent
* rev - VARCHAR(255) - Revision of snow agent
* sitename - VARCHAR(255) - Name of site
* configname - VARCHAR(255) - Configuration name
* server_address - VARCHAR(255) - Server address
* client_cert - VARCHAR(255) - Path to client cert
* client_cert_password - VARCHAR(255) - Password of client cert
* drop_location - TEXT - Location of scan results
* software_scan_running_processes - Boolean - Should scan running processes
* software_scan_jar - Boolean - Should scan JAR files
* http_ssl_verify - Boolean - Verify HTTP SSL
