  var   auctionDate   = 0;
  var   startTime   = (new   Date()).getTime();
  var   Temp;    
  var   timerID   =   null;    
  var   timerRunning   =   false;   
  function   showtime()    
  {    
          now   =   new   Date();
          var   ts=parseInt((startTime-now.getTime())/1000)+auctionDate;
          var   dateLeft   =   0;
          var   hourLeft   =   0;
          var   minuteLeft   =   0;
          var   secondLeft   =   0;
          if(ts   <   0)
          {
                  ts   =   0;
                  CurHour   =   0;
                  CurMinute   =   0;
                  CurSecond   =   0; 
          }   else   {
                  dateLeft   =parseInt(ts/86400);
                  ts   =   ts   -   dateLeft   *   86400;
                  hourLeft   =   parseInt(ts/3600);
                  ts   =   ts   -   hourLeft   *   3600;
                  minuteLeft   =   parseInt(ts/60);
                  secondLeft   =   ts   -   minuteLeft   *   60;
          }    
          if(hourLeft   <   10)   hourLeft   =   '0'   +hourLeft;    
          if(minuteLeft   <   10)   minuteLeft   =   '0'   +minuteLeft;    
          if(secondLeft<10)   secondLeft='0'+secondLeft;    
          if(   dateLeft   >   0   )    
                  dateLeft   =      "<strong>"+dateLeft+"</strong>天";    
          else    
                  dateLeft   =   "";    
          if(   hourLeft   >   0   )    
                  hourLeft   =   "<strong>"+hourLeft+"</strong>小时";    
          else    
          {    
                  if(   dateLeft   !=   ""   )    
                          hourLeft   =   "<strong>00</strong>小时";    
                  else    
                          hourLeft   =   "";    
          }    
          if(   minuteLeft   >   0   )    
                  minuteLeft   =     "<strong>"+minuteLeft+"</strong>分钟";    
          else    
          {    
                  if(   dateLeft   !=""   ||   hourLeft   !=   "")    
                          minuteLeft   =   "<strong>00</span>分钟</li>";  
                  else    
                          minuteLeft   =   "";    
          }    
          if(   secondLeft   >   0   )    
                  secondLeft   =      "<strong>"+secondLeft+"</strong>秒";    
          else    
          {    
                  if(   dateLeft   !=""   ||   hourLeft   !=   ""   ||   minuteLeft   !=   "")    
                          secondLeft   =   "<strong>00</strong>秒";     
                  else    
                          secondLeft   =   "";    
          }    
  if   (dateLeft   ==   '')   {    
        Temp=dateLeft+hourLeft+minuteLeft+secondLeft   ;    
  }else   {    
        Temp=dateLeft+hourLeft+minuteLeft;    
  }    
          if(dateLeft   <=0   &&   hourLeft<=0   &&   minuteLeft<=0   &&   secondLeft   <=0)    
          {    
                  Temp   =   "成交结束";    
                  stopclock();    
          }    
          if   (document.getElementById('counter'))   document.getElementById('counter').innerHTML=Temp;    
          timerID   =   setTimeout("showtime()",1000);    
          timerRunning   =   true; 
}    
  function   stopclock()    
  {    
          if(timerRunning)    
                  clearTimeout(timerID);    
          timerRunning   =   false;    
  }    
  function   macauclock(time)    
  {    
      auctionDate=time
          stopclock();    
          showtime();    
  }    
    

      
   