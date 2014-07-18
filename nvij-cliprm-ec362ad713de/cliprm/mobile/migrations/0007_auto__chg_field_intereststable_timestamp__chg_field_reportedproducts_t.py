# -*- coding: utf-8 -*-
import datetime
from south.db import db
from south.v2 import SchemaMigration
from django.db import models


class Migration(SchemaMigration):

    def forwards(self, orm):

        # Changing field 'Intereststable.timestamp'
        db.alter_column(u'interestsTable', 'timestamp', self.gf('django.db.models.fields.DateTimeField')(auto_now_add=True))

        # Changing field 'Reportedproducts.timestamp'
        db.alter_column(u'reportedProducts', 'timestamp', self.gf('django.db.models.fields.DateTimeField')(auto_now_add=True))

        # Changing field 'Productdetail.timestamp'
        db.alter_column(u'productDetail', 'timestamp', self.gf('django.db.models.fields.DateTimeField')(auto_now_add=True))

        # Changing field 'Clipstable.timestamp'
        db.alter_column(u'clipsTable', 'timestamp', self.gf('django.db.models.fields.DateTimeField')(auto_now_add=True))

    def backwards(self, orm):

        # Changing field 'Intereststable.timestamp'
        db.alter_column(u'interestsTable', 'timestamp', self.gf('django.db.models.fields.DateTimeField')())

        # Changing field 'Reportedproducts.timestamp'
        db.alter_column(u'reportedProducts', 'timestamp', self.gf('django.db.models.fields.DateTimeField')())

        # Changing field 'Productdetail.timestamp'
        db.alter_column(u'productDetail', 'timestamp', self.gf('django.db.models.fields.DateTimeField')())

        # Changing field 'Clipstable.timestamp'
        db.alter_column(u'clipsTable', 'timestamp', self.gf('django.db.models.fields.DateTimeField')())

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
            'timestamp': ('django.db.models.fields.DateTimeField', [], {'auto_now_add': 'True', 'blank': 'True'}),
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
            u'id': ('django.db.models.fields.AutoField', [], {'primary_key': 'True'}),
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
            'timestamp': ('django.db.models.fields.DateTimeField', [], {'auto_now_add': 'True', 'blank': 'True'}),
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
            'timestamp': ('django.db.models.fields.DateTimeField', [], {'auto_now_add': 'True', 'blank': 'True'}),
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
            'similar': ('django.db.models.fields.related.ForeignKey', [], {'blank': 'True', 'related_name': "u'similar_product'", 'null': 'True', 'db_column': "u'similar'", 'to': u"orm['mobile.Productdetail']"})
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
            'timestamp': ('django.db.models.fields.DateTimeField', [], {'auto_now_add': 'True', 'blank': 'True'}),
            'userid': ('django.db.models.fields.BigIntegerField', [], {'null': 'True', 'blank': 'True'})
        },
        u'mobile.tagtable': {
            'Meta': {'object_name': 'Tagtable', 'db_table': "u'tagTable'"},
            'id': ('django.db.models.fields.IntegerField', [], {'primary_key': 'True'}),
            'product': ('django.db.models.fields.related.ForeignKey', [], {'to': u"orm['mobile.Productdetail']", 'null': 'True', 'db_column': "u'productid'", 'blank': 'True'}),
            'taggeduserid': ('django.db.models.fields.related.ForeignKey', [], {'related_name': "u'tagtable_friendid'", 'db_column': "u'taggeduser'", 'to_field': "u'userid'", 'to': u"orm['mobile.CliprUser']", 'blank': 'True', 'null': 'True'}),
            'timestamp': ('django.db.models.fields.DateTimeField', [], {'auto_now_add': 'True', 'blank': 'True'}),
            'user': ('django.db.models.fields.related.ForeignKey', [], {'related_name': "u'tagtable_userid'", 'db_column': "u'userid'", 'to_field': "u'userid'", 'to': u"orm['mobile.CliprUser']", 'blank': 'True', 'null': 'True'})
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