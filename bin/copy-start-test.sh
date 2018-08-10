#!/bin/bash
# Script to automate distribution of start-test.sh to all plugins
# Met dit script worden alle start-test.sh in de site vervangen door de versie die in deze folder zit

script_directory=$(dirname "$0")
root_directory=""
plugin_directory=""
script="start-test.sh"
# voeg de namen van de plugins tot die testen hebben
plugins=(

)

# remove script + copy script to root
rm -f "${root_directory}/bin/${script}"
cp "${script_directory}/${script}" "${root_directory}/bin/${script}"

# remove script + copy script plugins
for i in "${plugins[@]}"
do
    rm -f "${plugin_directory}/${i}/bin/${script}"
    cp "${script_directory}/${script}" "${plugin_directory}/${i}/bin/${script}"
done
