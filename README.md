# digisos-v01
tree -L 8 -I 'vendor|node_modules|storage|bootstrap/cache' > structure.txt


# 1. Ajouter tous les fichiers modifiés à l'index
git add .
git commit -m "Correction"
git push origin main