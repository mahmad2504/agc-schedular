FROM mahmad2504/agc-baseimage
MAINTAINER Mumtaz Ahmad <ahmad-mumtaz1@hotmail.com>
RUN rm -fr /app
ADD src /app
RUN gem install taskjuggler
