result=$( git status | grep modified | grep -v "settings.py" | grep -v "clip.py" | grep -v  "tag.py" |
awk '{  first=match($0,"modified:")
    s=substr($0,first+12)
    print s}' )
git commit $result -m "$1"
git push