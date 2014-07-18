from django.shortcuts import redirect
from mobile.models import Productdetail
from mobile.utils.urlutils import set_query_parameter
from django.http import Http404

def getParam(site):
  if site == "flipkart":
    return "affid"
  elif site == "infibeam":
    return "trackId"
  elif site == "healthkart":
    return "affid"
  elif site == "itsourstudio":
    return "cliprRefer"
  elif site == "shortcircuit":
    return "cliprRefer"
  elif site == "bewakoof":
    return "cliprRefer"
  return "referrer"

def getValue(site):
	if site == "flipkart":
		return "infoclipri"
	elif site == "infibeam":
		return "clip"
	elif site == "healthkart":
		return "CliprL3C2ATRJ1P"
	elif site == "itsourstudio":
		return "clipr"
	elif site == "shortcircuit":
		return "clipr"
	elif site == "bewakoof":
		return "clipr"
	return "clipr"


def transfer_to_seller(request, pid):
	if not pid.isdigit():
		raise Http404
	res = Productdetail.objects.filter(productid=pid)
	for product in res:
		affiliateLink = set_query_parameter(product.link, getParam(product.siteid), getValue(product.siteid))
		return redirect(affiliateLink)
	raise Http404
