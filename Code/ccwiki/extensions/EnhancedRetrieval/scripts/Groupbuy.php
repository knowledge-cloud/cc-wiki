<script type="text/javascript" src="../ajax-solr/lib/core/Core.js"></script>
<script type="text/javascript" src="../ajax-solr/lib/core/AbstractManager.js"></script>
<script type="text/javascript" src="../ajax-solr/lib/managers/Manager.jquery.js"></script>
<script type="text/javascript" src="../ajax-solr/lib/core/Parameter.js"></script>
<script type="text/javascript" src="../ajax-solr/lib/core/ParameterStore.js"></script>
<script>
var Manager;
(function ($) {
  $(function () {
    Manager = new AjaxSolr.Manager({
      solrUrl: 'http://evolvingweb.ca/solr/reuters/'
    });
    Manager.init();
  });
})(jQuery);
Manager.store.addByValue('q', '*:*');

Manager.doRequest();</script>