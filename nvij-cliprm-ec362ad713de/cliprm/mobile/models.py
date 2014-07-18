# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#	 * Rearrange models' order
# Feel free to rename the models, but don't rename db_table values or field names.
#
# Also note: You'll have to insert the output of 'django-admin.py sqlcustom [appname]'
# into your database.
from __future__ import unicode_literals
from datetime import datetime, timedelta

from django.db import connection, models
import hashlib

from django.contrib.auth.models import (
	BaseUserManager, AbstractBaseUser
)

import hashlib
from django.db.models.fields import IntegerField
from django.conf import settings


from south.modelsinspector import add_introspection_rules
add_introspection_rules([], ["^mobile\.models\.BigIntegerField"])

class BigIntegerField(IntegerField):
    empty_strings_allowed=False
    def get_internal_type(self):
        return "BigIntegerField"	
    def db_type(self,connection):
        return 'bigint' # Note this won't work with Oracle.


class UserManager(BaseUserManager):
	def create_user(self, username, userid, email=None, password=None, first_name=None, last_name=None, **extra_fields):
		if not username or not userid:
			raise ValueError('Users must have a username and userid')

		user = self.model(
			username=username,
			email=UserManager.normalize_email(email),
			first_name=first_name or '',
			last_name=last_name or '',
			userid=userid,
		)
		user.set_password(password)
		user.save(using=self._db)
		return user

	def create_superuser(self, username, email, password, first_name=None, last_name=None):
		user = self.create_user(
			username,
			email,
			1,
			password=password,
			first_name=first_name,
			last_name=last_name,
		)
		user.is_admin = True
		user.save(using=self._db)
		return user

class CliprUser(AbstractBaseUser):
	userid=BigIntegerField(unique=True, db_index=True)
	email = models.CharField(max_length=100L)
	accesstoken = models.CharField(max_length=500L, db_column='accessToken') # Field name made lowercase.
	expiresin = models.IntegerField(db_column='expiresIn', null=True) # Field name made lowercase.
	first_name = models.CharField(max_length=100L,null=True, db_column='firstname')
	last_name = models.CharField(max_length=100L,null=True, db_column='lastname')
	fullname = models.CharField(max_length=200L)
	birthday = models.CharField(max_length=100L)
	hometown = models.CharField(max_length=100L)
	location = models.CharField(max_length=100L)
	gender = models.CharField(max_length=100L)
	education = models.CharField(max_length=500L)
	work = models.CharField(max_length=500L)
	time = models.DateTimeField(null=True)
	tourshown = models.IntegerField(null=True, blank=True)
	username = models.CharField(max_length=200L, unique=True, db_column='fbname')
	magshown = models.IntegerField(null=True)
	refershown = models.IntegerField(null=True)
	is_active = models.BooleanField(default=True)
	is_admin = models.BooleanField(default=False)

	objects = UserManager()

	USERNAME_FIELD = 'username'
	REQUIRED_FIELDS = ['email', 'first_name', 'last_name']

	def get_full_name(self):
		return self.first_name + " " + self.last_name
	def get_short_name(self):
		return self.first_name
	@property
	def is_staff(self):
		return self.is_admin
	def __unicode__(self):
		return self.first_name + " " + self.last_name
	def has_perm(self, perm, obj=None):
		return True
	def has_module_perms(self, app_label):
		return True

	@property
	def work_as_list(self):
		return self.work.split('---')
	@property
	def education_as_list(self):
		return self.education.split('---')

	class Meta:
		db_table = 'fbdata'

class Accesstokentable(models.Model):
	accesstoken = models.CharField(max_length=500L, db_column='accessToken') # Field name made lowercase.
	extractionleft = models.IntegerField(db_column='extractionLeft') # Field name made lowercase.
	time = models.DateTimeField()
	id = models.IntegerField(primary_key=True)
	class Meta:
		db_table = 'accessTokenTable'

class Activitytable(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.ForeignKey('CliprUser', null=True, to_field='userid',db_column='userid', blank=True, related_name='activity_actor_userid')
	action = models.CharField(max_length=100L)
	objuserid = models.ForeignKey('CliprUser', to_field='userid', null=True, db_column='objuserid', blank=True, related_name='activity_actee_userid')
	objproduct = models.ForeignKey('Productdetail', null=True, db_column='objproductid', blank=True, related_name='activity_productid')
	time = models.DateTimeField()
	board = models.CharField(max_length=200L, blank=True)
	@property
	def india_time(self):
		return self.time+timedelta(hours=5,minutes=30)
	class Meta:
		db_table = 'activityTable'

class Addedproduct(models.Model):
	id = models.IntegerField(primary_key=True)
	link = models.CharField(max_length=500L)
	product = models.ForeignKey('Productdetail', null=True, db_column='productid', blank=True)
	user = models.ForeignKey('CliprUser', null=True, blank=True, to_field='userid', db_column='userid')
	time = models.DateTimeField()
	repeatadd = models.IntegerField(null=True, blank=True)
	class Meta:
		db_table = 'addedProduct'

class Affiliateaction(models.Model):
	id = models.IntegerField(primary_key=True)
	url = models.CharField(max_length=300L, blank=True)
	referrer = models.CharField(max_length=100L, blank=True)
	transaction = models.CharField(max_length=50L, blank=True)
	state = models.CharField(max_length=500L, blank=True)
	time = models.DateTimeField()
	class Meta:
		db_table = 'affiliateAction'

class Agemap(models.Model):
	id = models.IntegerField(primary_key=True)
	lower = models.IntegerField(null=True, blank=True)
	upper = models.IntegerField(null=True, blank=True)
	tag = models.CharField(max_length=20L)
	class Meta:
		db_table = 'ageMap'

class Alltags(models.Model):
	id = models.IntegerField(primary_key=True)
	name = models.CharField(max_length=200L)
	alternatives = models.CharField(max_length=300L, blank=True)
	def _get_readable_name(self):
		"Returns the product's image hash for first image."
		return self.name.replace('_',' ')
	tagname_text = property(_get_readable_name)

	class Meta:
		db_table = 'allTags'

class Cliprtagtable(models.Model):
	id = models.IntegerField(primary_key=True)
	product = models.ForeignKey('Productdetail', db_column='productid')
	cliprtag = models.ForeignKey('Alltags', null=True, db_column='cliprTag', blank=True) # Field name made lowercase.
	userid = models.BigIntegerField(null=True, blank=True)
	class Meta:
		db_table = 'cliprTagTable'

class Clipstable(models.Model):
	id = models.IntegerField(primary_key=True)
	user = models.ForeignKey('CliprUser', to_field='userid', null=True, db_column='userid', blank=True)
	product = models.ForeignKey('Productdetail', db_column='productid')
	timestamp = models.DateTimeField(auto_now_add=True)
	cliptag = models.CharField(max_length=100L, db_column='clipTag') # Field name made lowercase.
	points = models.IntegerField()
	class Meta:
		db_table = 'clipsTable'
	

class Collageproducts(models.Model):
	id = models.IntegerField(primary_key=True)
	cid = models.IntegerField()
	productid = models.IntegerField()
	class Meta:
		db_table = 'collageProducts'

class Collages(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.BigIntegerField(null=True, blank=True)
	collage = models.TextField(blank=True)
	heading = models.CharField(max_length=400L, blank=True)
	description = models.CharField(max_length=1000L, blank=True)
	class Meta:
		db_table = 'collages'

class Companyinfo(models.Model):
	siteid = models.CharField(max_length=50L, db_column='siteId', blank=True) # Field name made lowercase.
	name = models.CharField(max_length=100L, blank=True)
	logo = models.CharField(max_length=100L, blank=True)
	id = models.IntegerField(primary_key=True)
	class Meta:
		db_table = 'companyinfo'

class Contestentry(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.BigIntegerField()
	productid = models.IntegerField()
	time = models.DateTimeField()
	tagline = models.CharField(max_length=1000L, blank=True)
	class Meta:
		db_table = 'contestEntry'

class Deletedproducts(models.Model):
	id = models.IntegerField(primary_key=True)
	product = models.ForeignKey('Productdetail', null=True, db_column='productid', blank=True)
	time = models.DateTimeField()
	class Meta:
		db_table = 'deletedProducts'

class Emailcommunication(models.Model):
	user = models.ForeignKey('CliprUser', to_field='userid', primary_key=True, db_column='userid')
	emailgift = models.IntegerField(default=1)
	emaildeal = models.IntegerField(default=1)
	emailbulk = models.IntegerField(default=1)
	fbactivitypost = models.IntegerField(default=1)
	fbstreampost = models.IntegerField(default=-1)
	class Meta:
		db_table = 'emailCommunication'

class Fbfriend(models.Model):
	userid = models.ForeignKey('CliprUser', to_field='userid', db_column='userid', related_name='fbfriend_user')
	friendid = models.ForeignKey('CliprUser', to_field='userid', db_column='friendid', related_name='fbfriend_friend')
	follow = models.IntegerField()
	class Meta:
		db_table = 'fbfriend'

class Gendermap(models.Model):
	id = models.IntegerField(primary_key=True)
	tag = models.CharField(max_length=20L)
	class Meta:
		db_table = 'genderMap'

class Grouptable(models.Model):
	id = models.IntegerField(primary_key=True)
	groupname = models.CharField(max_length=200L, blank=True)
	alternatives = models.CharField(max_length=300L, blank=True)
	def _get_readable_name(self):
		"Returns the product's image hash for first image."
		return self.groupname.replace('_',' ')
	groupname_text = property(_get_readable_name)
	class Meta:
		db_table = 'groupTable'

class Intereststable(models.Model):
	id = models.IntegerField(primary_key=True)
	user = models.ForeignKey('CliprUser',to_field='userid', related_name='interest_uid', db_column='userid', null=True, blank=True)
	interest = models.ForeignKey('Alltags', db_column='interest')
	source = models.ForeignKey('CliprUser',to_field='userid', db_column='source',related_name='interest_sid', null=True, blank=True)
	timestamp = models.DateTimeField(auto_now_add=True)
	class Meta:
		db_table = 'interestsTable'

class Invitefriend(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.ForeignKey(CliprUser, to_field='userid', null=True, db_column='userid', blank=True)
	friendid = models.BigIntegerField(null=True, blank=True)
	requestid = models.BigIntegerField()
	time = models.DateTimeField()
	class Meta:
		db_table = 'inviteFriend'

class Invitestats(models.Model):
	id = models.IntegerField(primary_key=True)
	referrer = models.BigIntegerField(null=True, blank=True)
	userid = models.BigIntegerField(null=True, blank=True)
	class Meta:
		db_table = 'inviteStats'

class Likesuniverse(models.Model):
	id = models.IntegerField(primary_key=True)
	likes = models.CharField(max_length=500L)
	category = models.CharField(max_length=50L)
	source = models.IntegerField()
	class Meta:
		db_table = 'likesUniverse'

class Loginactivity(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.BigIntegerField(null=True, blank=True)
	time = models.DateTimeField()
	class Meta:
		db_table = 'loginActivity'

class Notificationtime(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.ForeignKey(CliprUser, to_field='userid', null=True, db_column='userid', blank=True)
	notification_clear_time = models.DateTimeField()
	class Meta:
		db_table = 'notificationTime'

class Prodpricehistory(models.Model):
	product = models.ForeignKey('Productdetail', primary_key=True, db_column='productid')
	history = models.TextField(blank=True)
	class Meta:
		db_table = 'prodPriceHistory'

class Prodtags(models.Model):
	id = models.IntegerField(primary_key=True)
	prodtag = models.CharField(max_length=250L, db_column='prodTag') # Field name made lowercase.
	taginfo = models.TextField(db_column='tagInfo', blank=True) # Field name made lowercase.
	class Meta:
		db_table = 'prodTags'

class Productdetail(models.Model):
	productid = models.IntegerField(primary_key=True)
	siteid = models.CharField(max_length=50L, db_column='siteId', blank=True) # Field name made lowercase.
	link = models.CharField(max_length=500L)
	title = models.CharField(max_length=200L)
	markprice = models.IntegerField()
	price = models.IntegerField()
	description = models.TextField(blank=True)
	description2 = models.TextField(blank=True)
	recid = models.TextField(blank=True)
	image = models.CharField(max_length=2000L, blank=True)
	buylink = models.CharField(max_length=200L, blank=True)
	category = models.CharField(max_length=400L, blank=True)
	demographics = models.CharField(max_length=20L, blank=True)
	timestamp = models.DateTimeField(auto_now_add=True)
	age = models.IntegerField(null=True, blank=True)
	sex = models.IntegerField(null=True, blank=True)
	uniqueid = models.CharField(max_length=200L, db_column='uniqueId', blank=True) # Field name made lowercase.
	imagequality = models.IntegerField(null=True, db_column='imageQuality', blank=True) # Field name made lowercase.
	shippingcost = models.CharField(max_length=500L, blank=True)
	availability = models.CharField(max_length=200L, blank=True)
	delivery = models.CharField(max_length=500L, blank=True)
	score = models.FloatField(null=True, blank=True)
	def _get_image_hash(self):
		"Returns the product's image hash for first image."
		return hashlib.md5( self.image.split('$$$')[0] ).hexdigest()
	def _get_product_name_for_url(self):
		"Returns the product's image hash for first image."
		return ('-'.join(self.title.split()))
	def _get_images_hash(self):
		"Returns the image hash for all images."
		images = map(lambda x:hashlib.md5( x ).hexdigest(), self.image.split('$$$'))
		return images
	hashed_image = property(_get_image_hash)
	hashed_images = property(_get_images_hash)
	title_url = property(_get_product_name_for_url)
	class Meta:
		db_table = 'productDetail'

class Productgender(models.Model):
	id = models.IntegerField(primary_key=True)
	product = models.ForeignKey(Productdetail, null=True, db_column='productid', blank=True)
	gender = models.IntegerField(null=True, blank=True)
	class Meta:
		db_table = 'productGender'

class Productgroup(models.Model):
	id = models.IntegerField(primary_key=True)
	product = models.ForeignKey(Productdetail, null=True, db_column='productid', blank=True)
	groupid = models.ForeignKey(Grouptable, null=True, db_column='groupid', blank=True)
	userid = models.BigIntegerField(null=True, blank=True)
	class Meta:
		db_table = 'productGroup'

class Producttopsimilar(models.Model):
	id = models.IntegerField(primary_key=True)
	productid = models.ForeignKey('Productdetail', null=True, db_column='productid', blank=True)
	similar = models.ForeignKey('Productdetail', null=True, db_column='similar', blank=True, related_name='similar_product')
	score = models.FloatField(null=True, blank=True)
	class Meta:
		db_table = 'productTopSimilar'

class Productview(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.BigIntegerField(null=True, blank=True)
	product = models.ForeignKey(Productdetail, db_column='productid')
	views = models.IntegerField(null=True, blank=True)
	class Meta:
		db_table = 'productView'

class Reportedproducts(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.BigIntegerField(null=True, blank=True)
	product = models.ForeignKey(Productdetail, null=True, db_column='productid', blank=True)
	timestamp = models.DateTimeField(auto_now_add=True)
	class Meta:
		db_table = 'reportedProducts'

class Tagtable(models.Model):
	id = models.IntegerField(primary_key=True)
	user = models.ForeignKey('CliprUser', to_field='userid', null=True, db_column='userid', blank=True,related_name='tagtable_userid')
	product = models.ForeignKey(Productdetail, null=True, db_column='productid', blank=True)
	taggeduser = models.ForeignKey('CliprUser', to_field='userid', null=True, db_column='taggeduser', blank=True,related_name='tagtable_friendid')
	timestamp = models.DateTimeField(auto_now_add=True)
	class Meta:
		db_table = 'tagTable'

class Topdrops(models.Model):
	id = models.IntegerField(primary_key=True)
	productid = models.IntegerField()
	oldprice = models.IntegerField(null=True, blank=True)
	numdays = models.IntegerField()
	pricedrop = models.FloatField(null=True, blank=True)
	class Meta:
		db_table = 'topDrops'

class Usertopproducts(models.Model):
	id = models.IntegerField(primary_key=True)
	userid = models.BigIntegerField(null=True, blank=True)
	product = models.ForeignKey(Productdetail, null=True, db_column='productid', blank=True)
	score = models.FloatField(null=True, blank=True)
	requester = models.BigIntegerField(null=True, blank=True)
	time = models.DateTimeField()
	class Meta:
		db_table = 'userTopProducts'
