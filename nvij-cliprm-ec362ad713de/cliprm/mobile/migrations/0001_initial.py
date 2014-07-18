# -*- coding: utf-8 -*-
import datetime
from south.db import db
from south.v2 import SchemaMigration
from django.db import models


class Migration(SchemaMigration):

    def forwards(self, orm):
        # Adding model 'CliprUser'
        db.create_table(u'fbdata', (
            (u'id', self.gf('django.db.models.fields.AutoField')(primary_key=True)),
            ('password', self.gf('django.db.models.fields.CharField')(max_length=128)),
            ('last_login', self.gf('django.db.models.fields.DateTimeField')(default=datetime.datetime.now)),
            ('userid', self.gf('mobile.models.BigIntegerField')(unique=True, db_index=True)),
            ('email', self.gf('django.db.models.fields.CharField')(max_length=50L)),
            ('accesstoken', self.gf('django.db.models.fields.CharField')(max_length=500L, db_column=u'accessToken')),
            ('expiresin', self.gf('django.db.models.fields.IntegerField')(null=True, db_column=u'expiresIn')),
            ('first_name', self.gf('django.db.models.fields.CharField')(max_length=100L, null=True, db_column=u'firstname')),
            ('last_name', self.gf('django.db.models.fields.CharField')(max_length=100L, null=True, db_column=u'lastname')),
            ('fullname', self.gf('django.db.models.fields.CharField')(max_length=200L)),
            ('birthday', self.gf('django.db.models.fields.CharField')(max_length=100L)),
            ('hometown', self.gf('django.db.models.fields.CharField')(max_length=100L)),
            ('location', self.gf('django.db.models.fields.CharField')(max_length=100L)),
            ('gender', self.gf('django.db.models.fields.CharField')(max_length=100L)),
            ('education', self.gf('django.db.models.fields.CharField')(max_length=500L)),
            ('work', self.gf('django.db.models.fields.CharField')(max_length=500L)),
            ('time', self.gf('django.db.models.fields.DateTimeField')(null=True)),
            ('tourshown', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
            ('username', self.gf('django.db.models.fields.CharField')(unique=True, max_length=200L, db_column=u'fbname')),
            ('magshown', self.gf('django.db.models.fields.IntegerField')(null=True)),
            ('refershown', self.gf('django.db.models.fields.IntegerField')(null=True)),
            ('is_active', self.gf('django.db.models.fields.BooleanField')(default=True)),
            ('is_admin', self.gf('django.db.models.fields.BooleanField')(default=False)),
        ))
        db.send_create_signal(u'mobile', ['CliprUser'])

        # Adding model 'Accesstokentable'
        db.create_table(u'accessTokenTable', (
            ('accesstoken', self.gf('django.db.models.fields.CharField')(max_length=500L, db_column=u'accessToken')),
            ('extractionleft', self.gf('django.db.models.fields.IntegerField')(db_column=u'extractionLeft')),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
        ))
        db.send_create_signal(u'mobile', ['Accesstokentable'])

        # Adding model 'Activitytable'
        db.create_table(u'activityTable', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.related.ForeignKey')(related_name=u'activity_actor_userid', db_column=u'userid', to_field=u'userid', to=orm['mobile.CliprUser'], blank=True, null=True)),
            ('action', self.gf('django.db.models.fields.CharField')(max_length=100L)),
            ('objuserid', self.gf('django.db.models.fields.related.ForeignKey')(related_name=u'activity_actee_userid', db_column=u'objuserid', to_field=u'userid', to=orm['mobile.CliprUser'], blank=True, null=True)),
            ('objproduct', self.gf('django.db.models.fields.related.ForeignKey')(blank=True, related_name=u'activity_productid', null=True, db_column=u'objproductid', to=orm['mobile.Productdetail'])),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
            ('board', self.gf('django.db.models.fields.CharField')(max_length=200L, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Activitytable'])

        # Adding model 'Addedproduct'
        db.create_table(u'addedProduct', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('link', self.gf('django.db.models.fields.CharField')(max_length=500L)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], null=True, db_column=u'productid', blank=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
            ('repeatadd', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Addedproduct'])

        # Adding model 'Affiliateaction'
        db.create_table(u'affiliateAction', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('url', self.gf('django.db.models.fields.CharField')(max_length=300L, blank=True)),
            ('referrer', self.gf('django.db.models.fields.CharField')(max_length=100L, blank=True)),
            ('transaction', self.gf('django.db.models.fields.CharField')(max_length=50L, blank=True)),
            ('state', self.gf('django.db.models.fields.CharField')(max_length=500L, blank=True)),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Affiliateaction'])

        # Adding model 'Agemap'
        db.create_table(u'ageMap', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('lower', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
            ('upper', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
            ('tag', self.gf('django.db.models.fields.CharField')(max_length=20L)),
        ))
        db.send_create_signal(u'mobile', ['Agemap'])

        # Adding model 'Alltags'
        db.create_table(u'allTags', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('name', self.gf('django.db.models.fields.CharField')(max_length=200L)),
            ('alternatives', self.gf('django.db.models.fields.CharField')(max_length=300L, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Alltags'])

        # Adding model 'Cliprtagtable'
        db.create_table(u'cliprTagTable', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], db_column=u'productid')),
            ('cliprtag', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Alltags'], null=True, db_column=u'cliprTag', blank=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Cliprtagtable'])

        # Adding model 'Clipstable'
        db.create_table(u'clipsTable', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('user', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.CliprUser'], to_field=u'userid', null=True, db_column=u'userid', blank=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], db_column=u'productid')),
            ('timestamp', self.gf('django.db.models.fields.DateTimeField')()),
            ('cliptag', self.gf('django.db.models.fields.CharField')(max_length=100L, db_column=u'clipTag')),
            ('points', self.gf('django.db.models.fields.IntegerField')()),
        ))
        db.send_create_signal(u'mobile', ['Clipstable'])

        # Adding model 'Collageproducts'
        db.create_table(u'collageProducts', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('cid', self.gf('django.db.models.fields.IntegerField')()),
            ('productid', self.gf('django.db.models.fields.IntegerField')()),
        ))
        db.send_create_signal(u'mobile', ['Collageproducts'])

        # Adding model 'Collages'
        db.create_table(u'collages', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('collage', self.gf('django.db.models.fields.TextField')(blank=True)),
            ('heading', self.gf('django.db.models.fields.CharField')(max_length=400L, blank=True)),
            ('description', self.gf('django.db.models.fields.CharField')(max_length=1000L, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Collages'])

        # Adding model 'Companyinfo'
        db.create_table(u'companyinfo', (
            ('siteid', self.gf('django.db.models.fields.CharField')(max_length=50L, db_column=u'siteId', blank=True)),
            ('name', self.gf('django.db.models.fields.CharField')(max_length=100L, blank=True)),
            ('logo', self.gf('django.db.models.fields.CharField')(max_length=100L, blank=True)),
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
        ))
        db.send_create_signal(u'mobile', ['Companyinfo'])

        # Adding model 'Contestentry'
        db.create_table(u'contestEntry', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')()),
            ('productid', self.gf('django.db.models.fields.IntegerField')()),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
            ('tagline', self.gf('django.db.models.fields.CharField')(max_length=1000L, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Contestentry'])

        # Adding model 'Deletedproducts'
        db.create_table(u'deletedProducts', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], null=True, db_column=u'productid', blank=True)),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Deletedproducts'])

        # Adding model 'Emailcommunication'
        db.create_table(u'emailCommunication', (
            ('userid', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.CliprUser'], to_field=u'userid', primary_key=True, db_column=u'userid')),
            ('emailgift', self.gf('django.db.models.fields.IntegerField')()),
            ('emaildeal', self.gf('django.db.models.fields.IntegerField')()),
            ('emailbulk', self.gf('django.db.models.fields.IntegerField')()),
            ('fbactivitypost', self.gf('django.db.models.fields.IntegerField')()),
            ('fbstreampost', self.gf('django.db.models.fields.IntegerField')()),
        ))
        db.send_create_signal(u'mobile', ['Emailcommunication'])

        # Adding model 'Fbfriend'
        db.create_table(u'fbfriend', (
            ('userid', self.gf('django.db.models.fields.related.ForeignKey')(related_name=u'fbfriend_user', to_field=u'userid', db_column=u'userid', to=orm['mobile.CliprUser'])),
            ('friendid', self.gf('django.db.models.fields.related.ForeignKey')(related_name=u'fbfriend_friend', to_field=u'userid', db_column=u'friendid', to=orm['mobile.CliprUser'])),
            ('follow', self.gf('django.db.models.fields.IntegerField')()),
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
        ))
        db.send_create_signal(u'mobile', ['Fbfriend'])

        # Adding model 'Gendermap'
        db.create_table(u'genderMap', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('tag', self.gf('django.db.models.fields.CharField')(max_length=20L)),
        ))
        db.send_create_signal(u'mobile', ['Gendermap'])

        # Adding model 'Grouptable'
        db.create_table(u'groupTable', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('groupname', self.gf('django.db.models.fields.CharField')(max_length=200L, blank=True)),
            ('alternatives', self.gf('django.db.models.fields.CharField')(max_length=300L, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Grouptable'])

        # Adding model 'Intereststable'
        db.create_table(u'interestsTable', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('interest', self.gf('django.db.models.fields.IntegerField')()),
            ('source', self.gf('django.db.models.fields.BigIntegerField')()),
            ('timestamp', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Intereststable'])

        # Adding model 'Invitefriend'
        db.create_table(u'inviteFriend', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.CliprUser'], to_field=u'userid', null=True, db_column=u'userid', blank=True)),
            ('friendid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('requestid', self.gf('django.db.models.fields.BigIntegerField')()),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Invitefriend'])

        # Adding model 'Invitestats'
        db.create_table(u'inviteStats', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('referrer', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Invitestats'])

        # Adding model 'Likesuniverse'
        db.create_table(u'likesUniverse', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('likes', self.gf('django.db.models.fields.CharField')(max_length=500L)),
            ('category', self.gf('django.db.models.fields.CharField')(max_length=50L)),
            ('source', self.gf('django.db.models.fields.IntegerField')()),
        ))
        db.send_create_signal(u'mobile', ['Likesuniverse'])

        # Adding model 'Loginactivity'
        db.create_table(u'loginActivity', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Loginactivity'])

        # Adding model 'Notificationtime'
        db.create_table(u'notificationTime', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.CliprUser'], to_field=u'userid', null=True, db_column=u'userid', blank=True)),
            ('notification_clear_time', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Notificationtime'])

        # Adding model 'Prodpricehistory'
        db.create_table(u'prodPriceHistory', (
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], primary_key=True, db_column=u'productid')),
            ('history', self.gf('django.db.models.fields.TextField')(blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Prodpricehistory'])

        # Adding model 'Prodtags'
        db.create_table(u'prodTags', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('prodtag', self.gf('django.db.models.fields.CharField')(max_length=250L, db_column=u'prodTag')),
            ('taginfo', self.gf('django.db.models.fields.TextField')(db_column=u'tagInfo', blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Prodtags'])

        # Adding model 'Productdetail'
        db.create_table(u'productDetail', (
            ('productid', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('siteid', self.gf('django.db.models.fields.CharField')(max_length=50L, db_column=u'siteId', blank=True)),
            ('link', self.gf('django.db.models.fields.CharField')(max_length=500L)),
            ('title', self.gf('django.db.models.fields.CharField')(max_length=200L)),
            ('markprice', self.gf('django.db.models.fields.IntegerField')()),
            ('price', self.gf('django.db.models.fields.IntegerField')()),
            ('description', self.gf('django.db.models.fields.TextField')(blank=True)),
            ('description2', self.gf('django.db.models.fields.TextField')(blank=True)),
            ('recid', self.gf('django.db.models.fields.TextField')(blank=True)),
            ('image', self.gf('django.db.models.fields.CharField')(max_length=2000L, blank=True)),
            ('buylink', self.gf('django.db.models.fields.CharField')(max_length=200L, blank=True)),
            ('category', self.gf('django.db.models.fields.CharField')(max_length=400L, blank=True)),
            ('demographics', self.gf('django.db.models.fields.CharField')(max_length=20L, blank=True)),
            ('timestamp', self.gf('django.db.models.fields.DateTimeField')()),
            ('age', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
            ('sex', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
            ('uniqueid', self.gf('django.db.models.fields.CharField')(max_length=200L, db_column=u'uniqueId', blank=True)),
            ('imagequality', self.gf('django.db.models.fields.IntegerField')(null=True, db_column=u'imageQuality', blank=True)),
            ('shippingcost', self.gf('django.db.models.fields.CharField')(max_length=500L, blank=True)),
            ('availability', self.gf('django.db.models.fields.CharField')(max_length=200L, blank=True)),
            ('delivery', self.gf('django.db.models.fields.CharField')(max_length=500L, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Productdetail'])

        # Adding model 'Productgender'
        db.create_table(u'productGender', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], null=True, db_column=u'productid', blank=True)),
            ('gender', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Productgender'])

        # Adding model 'Productgroup'
        db.create_table(u'productGroup', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], null=True, db_column=u'productid', blank=True)),
            ('groupid', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Grouptable'], null=True, db_column=u'groupid', blank=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Productgroup'])

        # Adding model 'Producttopsimilar'
        db.create_table(u'productTopSimilar', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('productid', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], null=True, db_column=u'productid', blank=True)),
            ('similar', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
            ('score', self.gf('django.db.models.fields.FloatField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Producttopsimilar'])

        # Adding model 'Productview'
        db.create_table(u'productView', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], db_column=u'productid')),
            ('views', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Productview'])

        # Adding model 'Reportedproducts'
        db.create_table(u'reportedProducts', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], null=True, db_column=u'productid', blank=True)),
            ('timestamp', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Reportedproducts'])

        # Adding model 'Tagtable'
        db.create_table(u'tagTable', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('productid', self.gf('django.db.models.fields.IntegerField')()),
            ('taggeduser', self.gf('django.db.models.fields.BigIntegerField')()),
            ('timestamp', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Tagtable'])

        # Adding model 'Topdrops'
        db.create_table(u'topDrops', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('productid', self.gf('django.db.models.fields.IntegerField')()),
            ('oldprice', self.gf('django.db.models.fields.IntegerField')(null=True, blank=True)),
            ('numdays', self.gf('django.db.models.fields.IntegerField')()),
            ('pricedrop', self.gf('django.db.models.fields.FloatField')(null=True, blank=True)),
        ))
        db.send_create_signal(u'mobile', ['Topdrops'])

        # Adding model 'Usertopproducts'
        db.create_table(u'userTopProducts', (
            ('id', self.gf('django.db.models.fields.IntegerField')(primary_key=True)),
            ('userid', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('product', self.gf('django.db.models.fields.related.ForeignKey')(to=orm['mobile.Productdetail'], null=True, db_column=u'productid', blank=True)),
            ('score', self.gf('django.db.models.fields.FloatField')(null=True, blank=True)),
            ('requester', self.gf('django.db.models.fields.BigIntegerField')(null=True, blank=True)),
            ('time', self.gf('django.db.models.fields.DateTimeField')()),
        ))
        db.send_create_signal(u'mobile', ['Usertopproducts'])


    def backwards(self, orm):
        # Deleting model 'CliprUser'
        db.delete_table(u'fbdata')

        # Deleting model 'Accesstokentable'
        db.delete_table(u'accessTokenTable')

        # Deleting model 'Activitytable'
        db.delete_table(u'activityTable')

        # Deleting model 'Addedproduct'
        db.delete_table(u'addedProduct')

        # Deleting model 'Affiliateaction'
        db.delete_table(u'affiliateAction')

        # Deleting model 'Agemap'
        db.delete_table(u'ageMap')

        # Deleting model 'Alltags'
        db.delete_table(u'allTags')

        # Deleting model 'Cliprtagtable'
        db.delete_table(u'cliprTagTable')

        # Deleting model 'Clipstable'
        db.delete_table(u'clipsTable')

        # Deleting model 'Collageproducts'
        db.delete_table(u'collageProducts')

        # Deleting model 'Collages'
        db.delete_table(u'collages')

        # Deleting model 'Companyinfo'
        db.delete_table(u'companyinfo')

        # Deleting model 'Contestentry'
        db.delete_table(u'contestEntry')

        # Deleting model 'Deletedproducts'
        db.delete_table(u'deletedProducts')

        # Deleting model 'Emailcommunication'
        db.delete_table(u'emailCommunication')

        # Deleting model 'Fbfriend'
        db.delete_table(u'fbfriend')

        # Deleting model 'Gendermap'
        db.delete_table(u'genderMap')

        # Deleting model 'Grouptable'
        db.delete_table(u'groupTable')

        # Deleting model 'Intereststable'
        db.delete_table(u'interestsTable')

        # Deleting model 'Invitefriend'
        db.delete_table(u'inviteFriend')

        # Deleting model 'Invitestats'
        db.delete_table(u'inviteStats')

        # Deleting model 'Likesuniverse'
        db.delete_table(u'likesUniverse')

        # Deleting model 'Loginactivity'
        db.delete_table(u'loginActivity')

        # Deleting model 'Notificationtime'
        db.delete_table(u'notificationTime')

        # Deleting model 'Prodpricehistory'
        db.delete_table(u'prodPriceHistory')

        # Deleting model 'Prodtags'
        db.delete_table(u'prodTags')

        # Deleting model 'Productdetail'
        db.delete_table(u'productDetail')

        # Deleting model 'Productgender'
        db.delete_table(u'productGender')

        # Deleting model 'Productgroup'
        db.delete_table(u'productGroup')

        # Deleting model 'Producttopsimilar'
        db.delete_table(u'productTopSimilar')

        # Deleting model 'Productview'
        db.delete_table(u'productView')

        # Deleting model 'Reportedproducts'
        db.delete_table(u'reportedProducts')

        # Deleting model 'Tagtable'
        db.delete_table(u'tagTable')

        # Deleting model 'Topdrops'
        db.delete_table(u'topDrops')

        # Deleting model 'Usertopproducts'
        db.delete_table(u'userTopProducts')


    models = {
        u'mobile.accesstokentable': {
            'Meta': {'object_name': 'Accesstokentable', 'db_table': "u'accessTokenTable'"},
            'accesstoken': ('django.db.models.fields.CharField', [], {'max_length': '500L', 'db_column': "u'accessToken'"}),
            'extractionleft': ('django.db.models.fields.IntegerField', [], {'db_column': "u'extractionLeft'"}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {})
        },
        u'mobile.activitytable': {
            'Meta': {'object_name': 'Activitytable', 'db_table': "u'activityTable'"},
            'action': ('django.db.models.fields.CharField', [], {'max_length': '100L'}),
            'board': ('django.db.models.fields.CharField', [], {'max_length': '200L', 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'objproduct': ('django.db.models.fields.related.ForeignKey', [], {'blank': 'True', 'related_name': "u'activity_productid'", 'null': 'True', 'db_column': "u'objproductid'", 'to': u"orm['mobile.Productdetail']"}),
            'objuserid': ('django.db.models.fields.related.ForeignKey', [], {'related_name': "u'activity_actee_userid'", 'db_column': "u'objuserid'", 'to_field': "u'userid'", 'to': u"orm['mobile.CliprUser']", 'blank': 'True', 'null': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.related.ForeignKey', [], {'related_name': "u'activity_actor_userid'", 'db_column': "u'userid'", 'to_field': "u'userid'", 'to': u"orm['mobile.CliprUser']", 'blank': 'True', 'null': 'True'})
        },
        u'mobile.addedproduct': {
            'Meta': {'object_name': 'Addedproduct', 'db_table': "u'addedProduct'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'link': ('django.db.models.fields.CharField', [], {'max_length': '500L'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'}),
            'repeatadd': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.affiliateaction': {
            'Meta': {'object_name': 'Affiliateaction', 'db_table': "u'affiliateAction'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'referrer': ('django.db.models.fields.CharField', [], {'max_length': '100L', 'blank': 'True'}),
            'state': ('django.db.models.fields.CharField', [], {'max_length': '500L', 'blank': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {}),
            'transaction': ('django.db.models.fields.CharField', [], {'max_length': '50L', 'blank': 'True'}),
            'url': ('django.db.models.fields.CharField', [], {'max_length': '300L', 'blank': 'True'})
        },
        u'mobile.agemap': {
            'Meta': {'object_name': 'Agemap', 'db_table': "u'ageMap'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'lower': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'}),
            'tag': ('django.db.models.fields.CharField', [], {'max_length': '20L'}),
            'upper': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.alltags': {
            'Meta': {'object_name': 'Alltags', 'db_table': "u'allTags'"},
            'alternatives': ('django.db.models.fields.CharField', [], {'max_length': '300L', 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'name': ('django.db.models.fields.CharField', [], {'max_length': '200L'})
        },
        u'mobile.cliprtagtable': {
            'Meta': {'object_name': 'Cliprtagtable', 'db_table': "u'cliprTagTable'"},
            'cliprtag': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Alltags']", 'null': 'True', 'db_column': "u'cliprTag'", 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'db_column': "u'productid'"}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.clipruser': {
            'Meta': {'object_name': 'CliprUser', 'db_table': "u'fbdata'"},
            'accesstoken': ('django.db.models.fields.CharField', [], {'max_length': '500L', 'db_column': "u'accessToken'"}),
            'birthday': ('django.db.models.fields.CharField', [], {'max_length': '100L'}),
            'education': ('django.db.models.fields.CharField', [], {'max_length': '500L'}),
            'email': ('django.db.models.fields.CharField', [], {'max_length': '50L'}),
            'expiresin': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'db_column': "u'expiresIn'"}),
            'first_name': ('django.db.models.fields.CharField', [], {'max_length': '100L', 'null': 'True', 'db_column': "u'firstname'"}),
            'fullname': ('django.db.models.fields.CharField', [], {'max_length': '200L'}),
            'gender': ('django.db.models.fields.CharField', [], {'max_length': '100L'}),
            'hometown': ('django.db.models.fields.CharField', [], {'max_length': '100L'}),
            u'id': ('django.db.models.fields.AutoField', [], {'primary_key': 'True'}),
            'is_active': ('django.db.models.fields.BooleanField', [], {'default': 'True'}),
            'is_admin': ('django.db.models.fields.BooleanField', [], {'default': 'False'}),
            'last_login': ('django.db.models.fields.DateTimeField', [], {'default': 'datetime.datetime.now'}),
            'last_name': ('django.db.models.fields.CharField', [], {'max_length': '100L', 'null': 'True', 'db_column': "u'lastname'"}),
            'location': ('django.db.models.fields.CharField', [], {'max_length': '100L'}),
            'magshown': ('django.db.models.fields.IntegerField', [], {'null': 'True'}),
            'password': ('django.db.models.fields.CharField', [], {'max_length': '128'}),
            'refershown': ('django.db.models.fields.IntegerField', [], {'null': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {'null': 'True'}),
            'tourshown': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'}),
            'userid': ('mobile.models.BigIntegerField', [], {'unique': 'True', 'db_index': 'True'}),
            'username': ('django.db.models.fields.CharField', [], {'unique': 'True', 'max_length': '200L', 'db_column': "u'fbname'"}),
            'work': ('django.db.models.fields.CharField', [], {'max_length': '500L'})
        },
        u'mobile.clipstable': {
            'Meta': {'object_name': 'Clipstable', 'db_table': "u'clipsTable'"},
            'cliptag': ('django.db.models.fields.CharField', [], {'max_length': '100L', 'db_column': "u'clipTag'"}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'points': ('django.db.models.fields.IntegerField', [], {}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'db_column': "u'productid'"}),
            'timestamp': ('django.db.models.fields.DateTimeField', [], {}),
            'user': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.CliprUser']", 'to_field': "u'userid'", 'null': 'True', 'db_column': "u'userid'", 'blank': 'True'})
        },
        u'mobile.collageproducts': {
            'Meta': {'object_name': 'Collageproducts', 'db_table': "u'collageProducts'"},
            'cid': ('django.db.models.fields.IntegerField', [], {}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'productid': ('django.db.models.fields.IntegerField', [], {})
        },
        u'mobile.collages': {
            'Meta': {'object_name': 'Collages', 'db_table': "u'collages'"},
            'collage': ('django.db.models.fields.TextField', [], {'blank': 'True'}),
            'description': ('django.db.models.fields.CharField', [], {'max_length': '1000L', 'blank': 'True'}),
            'heading': ('django.db.models.fields.CharField', [], {'max_length': '400L', 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.companyinfo': {
            'Meta': {'object_name': 'Companyinfo', 'db_table': "u'companyinfo'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'logo': ('django.db.models.fields.CharField', [], {'max_length': '100L', 'blank': 'True'}),
            'name': ('django.db.models.fields.CharField', [], {'max_length': '100L', 'blank': 'True'}),
            'siteid': ('django.db.models.fields.CharField', [], {'max_length': '50L', 'db_column': "u'siteId'", 'blank': 'True'})
        },
        u'mobile.contestentry': {
            'Meta': {'object_name': 'Contestentry', 'db_table': "u'contestEntry'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'productid': ('django.db.models.fields.IntegerField', [], {}),
            'tagline': ('django.db.models.fields.CharField', [], {'max_length': '1000L', 'blank': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {})
        },
        u'mobile.deletedproducts': {
            'Meta': {'object_name': 'Deletedproducts', 'db_table': "u'deletedProducts'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {})
        },
        u'mobile.emailcommunication': {
            'Meta': {'object_name': 'Emailcommunication', 'db_table': "u'emailCommunication'"},
            'emailbulk': ('django.db.models.fields.IntegerField', [], {}),
            'emaildeal': ('django.db.models.fields.IntegerField', [], {}),
            'emailgift': ('django.db.models.fields.IntegerField', [], {}),
            'fbactivitypost': ('django.db.models.fields.IntegerField', [], {}),
            'fbstreampost': ('django.db.models.fields.IntegerField', [], {}),
            'userid': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.CliprUser']", 'to_field': "u'userid'", 'primary_key': 'True', 'db_column': "u'userid'"})
        },
        u'mobile.fbfriend': {
            'Meta': {'object_name': 'Fbfriend', 'db_table': "u'fbfriend'"},
            'follow': ('django.db.models.fields.IntegerField', [], {}),
            'friendid': ('django.db.models.fields.related.ForeignKey', [], {'related_name': "u'fbfriend_friend'", 'to_field': "u'userid'", 'db_column': "u'friendid'", 'to': u"orm['mobile.CliprUser']"}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'userid': ('django.db.models.fields.related.ForeignKey', [], {'related_name': "u'fbfriend_user'", 'to_field': "u'userid'", 'db_column': "u'userid'", 'to': u"orm['mobile.CliprUser']"})
        },
        u'mobile.gendermap': {
            'Meta': {'object_name': 'Gendermap', 'db_table': "u'genderMap'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'tag': ('django.db.models.fields.CharField', [], {'max_length': '20L'})
        },
        u'mobile.grouptable': {
            'Meta': {'object_name': 'Grouptable', 'db_table': "u'groupTable'"},
            'alternatives': ('django.db.models.fields.CharField', [], {'max_length': '300L', 'blank': 'True'}),
            'groupname': ('django.db.models.fields.CharField', [], {'max_length': '200L', 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'})
        },
        u'mobile.intereststable': {
            'Meta': {'object_name': 'Intereststable', 'db_table': "u'interestsTable'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'interest': ('django.db.models.fields.IntegerField', [], {}),
            'source': ('django.db.models.fields.BigIntegerField', [], {}),
            'timestamp': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.invitefriend': {
            'Meta': {'object_name': 'Invitefriend', 'db_table': "u'inviteFriend'"},
            'friendid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'requestid': ('django.db.models.fields.BigIntegerField', [], {}),
            'time': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.CliprUser']", 'to_field': "u'userid'", 'null': 'True', 'db_column': "u'userid'", 'blank': 'True'})
        },
        u'mobile.invitestats': {
            'Meta': {'object_name': 'Invitestats', 'db_table': "u'inviteStats'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'referrer': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.likesuniverse': {
            'Meta': {'object_name': 'Likesuniverse', 'db_table': "u'likesUniverse'"},
            'category': ('django.db.models.fields.CharField', [], {'max_length': '50L'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'likes': ('django.db.models.fields.CharField', [], {'max_length': '500L'}),
            'source': ('django.db.models.fields.IntegerField', [], {})
        },
        u'mobile.loginactivity': {
            'Meta': {'object_name': 'Loginactivity', 'db_table': "u'loginActivity'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.notificationtime': {
            'Meta': {'object_name': 'Notificationtime', 'db_table': "u'notificationTime'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'notification_clear_time': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.CliprUser']", 'to_field': "u'userid'", 'null': 'True', 'db_column': "u'userid'", 'blank': 'True'})
        },
        u'mobile.prodpricehistory': {
            'Meta': {'object_name': 'Prodpricehistory', 'db_table': "u'prodPriceHistory'"},
            'history': ('django.db.models.fields.TextField', [], {'blank': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'primary_key': 'True', 'db_column': "u'productid'"})
        },
        u'mobile.prodtags': {
            'Meta': {'object_name': 'Prodtags', 'db_table': "u'prodTags'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'prodtag': ('django.db.models.fields.CharField', [], {'max_length': '250L', 'db_column': "u'prodTag'"}),
            'taginfo': ('django.db.models.fields.TextField', [], {'db_column': "u'tagInfo'", 'blank': 'True'})
        },
        u'mobile.productdetail': {
            'Meta': {'object_name': 'Productdetail', 'db_table': "u'productDetail'"},
            'age': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'}),
            'availability': ('django.db.models.fields.CharField', [], {'max_length': '200L', 'blank': 'True'}),
            'buylink': ('django.db.models.fields.CharField', [], {'max_length': '200L', 'blank': 'True'}),
            'category': ('django.db.models.fields.CharField', [], {'max_length': '400L', 'blank': 'True'}),
            'delivery': ('django.db.models.fields.CharField', [], {'max_length': '500L', 'blank': 'True'}),
            'demographics': ('django.db.models.fields.CharField', [], {'max_length': '20L', 'blank': 'True'}),
            'description': ('django.db.models.fields.TextField', [], {'blank': 'True'}),
            'description2': ('django.db.models.fields.TextField', [], {'blank': 'True'}),
            'image': ('django.db.models.fields.CharField', [], {'max_length': '2000L', 'blank': 'True'}),
            'imagequality': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'db_column': "u'imageQuality'", 'blank': 'True'}),
            'link': ('django.db.models.fields.CharField', [], {'max_length': '500L'}),
            'markprice': ('django.db.models.fields.IntegerField', [], {}),
            'price': ('django.db.models.fields.IntegerField', [], {}),
            'productid': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'recid': ('django.db.models.fields.TextField', [], {'blank': 'True'}),
            'sex': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'}),
            'shippingcost': ('django.db.models.fields.CharField', [], {'max_length': '500L', 'blank': 'True'}),
            'siteid': ('django.db.models.fields.CharField', [], {'max_length': '50L', 'db_column': "u'siteId'", 'blank': 'True'}),
            'timestamp': ('django.db.models.fields.DateTimeField', [], {}),
            'title': ('django.db.models.fields.CharField', [], {'max_length': '200L'}),
            'uniqueid': ('django.db.models.fields.CharField', [], {'max_length': '200L', 'db_column': "u'uniqueId'", 'blank': 'True'})
        },
        u'mobile.productgender': {
            'Meta': {'object_name': 'Productgender', 'db_table': "u'productGender'"},
            'gender': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'})
        },
        u'mobile.productgroup': {
            'Meta': {'object_name': 'Productgroup', 'db_table': "u'productGroup'"},
            'groupid': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Grouptable']", 'null': 'True', 'db_column': "u'groupid'", 'blank': 'True'}),
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.producttopsimilar': {
            'Meta': {'object_name': 'Producttopsimilar', 'db_table': "u'productTopSimilar'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'productid': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'}),
            'score': ('django.db.models.fields.FloatField', [], {'null': 'True', 'blank': 'True'}),
            'similar': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.productview': {
            'Meta': {'object_name': 'Productview', 'db_table': "u'productView'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'db_column': "u'productid'"}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'}),
            'views': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.reportedproducts': {
            'Meta': {'object_name': 'Reportedproducts', 'db_table': "u'reportedProducts'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'}),
            'timestamp': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.tagtable': {
            'Meta': {'object_name': 'Tagtable', 'db_table': "u'tagTable'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'productid': ('django.db.models.fields.IntegerField', [], {}),
            'taggeduser': ('django.db.models.fields.BigIntegerField', [], {}),
            'timestamp': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.topdrops': {
            'Meta': {'object_name': 'Topdrops', 'db_table': "u'topDrops'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'numdays': ('django.db.models.fields.IntegerField', [], {}),
            'oldprice': ('django.db.models.fields.IntegerField', [], {'null': 'True', 'blank': 'True'}),
            'pricedrop': ('django.db.models.fields.FloatField', [], {'null': 'True', 'blank': 'True'}),
            'productid': ('django.db.models.fields.IntegerField', [], {})
        },
        u'mobile.usertopproducts': {
            'Meta': {'object_name': 'Usertopproducts', 'db_table': "u'userTopProducts'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'}),
            'requester': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'}),
            'score': ('django.db.models.fields.FloatField', [], {'null': 'True', 'blank': 'True'}),
            'time': ('django.db.models.fields.DateTimeField', [], {}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        }
    }

    complete_apps = ['mobile']