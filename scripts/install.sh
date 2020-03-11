#!/bin/bash

# snowagent controller
CTL="${BASEURL}index.php?/module/snowagent/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/snowagent.py" -o "${MUNKIPATH}preflight.d/snowagent.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/snowagent.py"
    
	# Touch the cache file to prevent errors
	mkdir -p "${MUNKIPATH}preflight.d/cache"
	touch "${MUNKIPATH}preflight.d/cache/snowagent.plist"

	# Set preference to include this file in the preflight check
	setreportpref "snowagent" "${CACHEPATH}snowagent.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/snowagent.py"

	# Signal that we had an error
	ERR=1
fi


