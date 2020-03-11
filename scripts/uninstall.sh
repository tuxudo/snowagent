#!/bin/bash

# Remove snowagent script
rm -f "${MUNKIPATH}preflight.d/snowagent.py"

# Remove snowagent.plist file
rm -f "${CACHEPATH}snowagent.plist"
