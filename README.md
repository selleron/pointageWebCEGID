# pointageWebCEGID
interface web point le pre pointage des jours de travail
Repository principal  (http)  : https://github.com/selleron/pointageWebCEGID.git
Repository secondaire (ssl)   :    pi@kyoto-pi:/var/lib/git/pointageWebCEGID.git


git clone <repository> [<nom local>]
> git clone pi@kyoto-pi:/var/lib/git/pointageWebCEGID.git
> git fetch

After the clone, a plain git fetch without arguments will update all the remote-tracking branches

 
connaitre l'origine 
> git remote show origin

# changer l'origine
#######################################
la branche "origin" est la branche par defaut pour les opération (push, fetch, pull,...)
pour modifier "origin" il suffit de :
git remote rm origin
git remote add origin pi@kyoto-pi:/var/lib/git/pointageWebCEGID.git


