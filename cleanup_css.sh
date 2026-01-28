#!/bin/bash

###############################################################################
# Script de Nettoyage CSS/SCSS - Projet Laravel 12
# Supprime les fichiers incompatibles et dupliqués
###############################################################################

echo "🧹 Nettoyage CSS/SCSS - Démarrage..."
echo ""

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Compteurs
DELETED=0
ERRORS=0

###############################################################################
# Fonction de suppression sécurisée
###############################################################################
safe_delete() {
    local file=$1
    if [ -f "$file" ]; then
        echo -e "${YELLOW}🗑️  Suppression: ${NC}$file"
        rm "$file"
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}   ✅ Supprimé avec succès${NC}"
            ((DELETED++))
        else
            echo -e "${RED}   ❌ Erreur lors de la suppression${NC}"
            ((ERRORS++))
        fi
    else
        echo -e "${YELLOW}⚠️  Fichier non trouvé (ignoré): ${NC}$file"
    fi
    echo ""
}

###############################################################################
# 1. SUPPRIMER TAILWIND CSS
###############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📦 Étape 1: Suppression de Tailwind CSS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

safe_delete "resources/css/app.css"

###############################################################################
# 2. SUPPRIMER DOUBLONS
###############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📦 Étape 2: Suppression des doublons"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

safe_delete "resources/css/media-manager.css"

###############################################################################
# 3. NETTOYER LE DOSSIER resources/css/ SI VIDE
###############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📦 Étape 3: Nettoyage du dossier resources/css/"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if [ -d "resources/css" ]; then
    # Vérifier si le dossier est vide
    if [ -z "$(ls -A resources/css 2>/dev/null)" ]; then
        echo -e "${YELLOW}🗑️  Suppression du dossier vide: ${NC}resources/css/"
        rmdir resources/css
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}   ✅ Dossier supprimé avec succès${NC}"
            ((DELETED++))
        else
            echo -e "${RED}   ❌ Erreur lors de la suppression du dossier${NC}"
            ((ERRORS++))
        fi
    else
        echo -e "${YELLOW}⚠️  Dossier resources/css/ non vide:${NC}"
        ls -la resources/css/
        echo ""
        echo -e "${YELLOW}   Fichiers restants non supprimés (vérification manuelle requise)${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  Dossier resources/css/ n'existe pas (ignoré)${NC}"
fi
echo ""

###############################################################################
# 4. VÉRIFICATION DE LA STRUCTURE
###############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📦 Étape 4: Vérification de la structure"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "📁 Structure SCSS (sources):"
if [ -d "resources/scss" ]; then
    ls -lh resources/scss/
    echo ""
else
    echo -e "${RED}❌ Dossier resources/scss/ introuvable!${NC}"
    echo ""
fi

echo "📁 Structure CSS (production):"
if [ -d "public/css" ]; then
    ls -lh public/css/
    echo ""
else
    echo -e "${RED}❌ Dossier public/css/ introuvable!${NC}"
    echo ""
fi

###############################################################################
# 5. RECOMPILATION
###############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📦 Étape 5: Recompilation des assets"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v npm &> /dev/null; then
    echo "🔨 Compilation avec npm run dev..."
    npm run dev
    echo ""
    
    if [ -f "public/css/app.css" ]; then
        SIZE=$(du -h public/css/app.css | cut -f1)
        echo -e "${GREEN}✅ app.css compilé avec succès (${SIZE})${NC}"
    else
        echo -e "${RED}❌ Erreur: app.css non généré${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  npm non trouvé - compilation manuelle requise${NC}"
    echo "   Exécutez: npm run dev"
fi
echo ""

###############################################################################
# RÉSUMÉ
###############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 RÉSUMÉ DU NETTOYAGE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "Fichiers/Dossiers supprimés: ${GREEN}${DELETED}${NC}"
echo -e "Erreurs rencontrées: ${RED}${ERRORS}${NC}"
echo ""

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ Nettoyage terminé avec succès!${NC}"
    echo ""
    echo "📋 Prochaines étapes:"
    echo "   1. php artisan view:clear"
    echo "   2. php artisan serve"
    echo "   3. Tester: http://localhost:8000/admin/dashboard"
else
    echo -e "${RED}⚠️  Nettoyage terminé avec des erreurs${NC}"
    echo "   Vérifiez les messages ci-dessus"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
