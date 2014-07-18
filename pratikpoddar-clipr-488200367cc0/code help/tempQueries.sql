SELECT * from (SELECT distinct likeTag, level, documentFrequency , count(*), count(*)*log(12190/(1+documentFrequency))*(1+level/7.0)  as num \
    from likesUniverse join fblike join likeCategorizeNormalized join likeTags on fblike.likeid = likesUniverse.id and  fblike.likeid = likeCategorizeNormalized.likeid  \
    and likeTags.likeTag = likeCategorizeNormalized.value where fblike.userid = 221000196 and (likeCategorizeNormalized.score > 3) and topicScore > 40 \
    and attribute != 'country' and attribute != 'nationality' and attribute != 'language' \
    and  attribute != 'gender' and value != 'media_common' and  value != 'organization' and value !='people' \
    and  value !='user' and value !='location' group by likeTag, level, documentFrequency order by num ) as t1 ;
