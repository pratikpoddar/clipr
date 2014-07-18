import struct
import Image
import scipy
import scipy.misc
import scipy.cluster

NUM_CLUSTERS = 5

im = Image.open('test5.jpg')
im = im.resize((150, 150))      # optional, to reduce time
ar = scipy.misc.fromimage(im)
arcenter1 = ar[50:100,50:100]
arcenter2 = ar[65:85,65:85]
arcenter3 = ar[65:80,65:80]
shape = ar.shape
shapecenter1 = arcenter1.shape
shapecenter2 = arcenter2.shape
shapecenter3 = arcenter3.shape
ar = ar.reshape(scipy.product(shape[:2]), shape[2])
arcenter1 = arcenter1.reshape(scipy.product(shapecenter1[:2]), shapecenter1[2])
arcenter2 = arcenter2.reshape(scipy.product(shapecenter2[:2]), shapecenter2[2])
arcenter3 = arcenter3.reshape(scipy.product(shapecenter3[:2]), shapecenter3[2])

ar = numpy.concatenate ((ar, arcenter1, arcenter2, arcenter3))

codes, dist = scipy.cluster.vq.kmeans(ar, NUM_CLUSTERS)
#print 'cluster centres:\n', codes

vecs, dist = scipy.cluster.vq.vq(ar, codes)         # assign codes
counts, bins = scipy.histogram(vecs, len(codes))    # count occurrences
print 'counts', counts
print 'codes', codes

index_max = scipy.argmax(counts)                    # find most frequent
peak = codes[index_max]
colour = ''.join(chr(c) for c in peak).encode('hex')
print 'most frequent is %s (#%s)' % (peak, colour)

#	Install before running
#	sudo pip install numpy
#	sudo apt-get install libatlas-base-dev gfortran
# 	sudo pip install scipy
