#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <stdbool.h>
#include <ctype.h>
#include <dirent.h>
#include <errno.h>

void ExecuteCommand(char **args,int output)
{
     pid_t pid = fork();
     if( pid < 0)
     {
         printf("Fork failed\n");
         exit(-1);
     }
     else if(pid > 0) // parent
     {
         return;
     }
     else
     {
          if(output==0)
          {
              int fd = open("/dev/null", O_WRONLY);
              dup2(fd,1);
              dup2(fd,2);
          }
          if (execvp(args[0], args) == -1)
              printf("Child exits\n");
          exit(-1);
     }
}

int IsFileExist(const char *fname)
{
    FILE *file;
    if ((file = fopen(fname, "r")))
    {
        fclose(file);
        return 1;
    }
    return 0;
}
int IsDirectoryExist(char *dirpath)
{
    DIR* dir = opendir(dirpath);
    if (dir)
    {
        /* Directory exists. */
        closedir(dir);
        return 1;
    }
    else if (ENOENT == errno)
    {
        /* Directory does not exist. */
        return 0;
    }
    else
    {
         /* opendir() failed for some other reason. */
         return 0;
    }
}
void main(int argc, char *argv)
{
    int status = 0;
    pid_t pid;
    char* args[10];
    if(!IsDirectoryExist("/var/lib/gems/2.1.0/gems/"))
    {
        printf("Needed packages missing \n");
        exit(-1);
    }
    printf("Schedular \n");
    if(!IsFileExist("data"))
    {
        args[0] = "wget";
        args[1] = "-O";
        args[2] = "data";
        args[3] = "https://github.com/mahmad2504/AgileGantt/blob/master/data?raw=tru";
        args[4] = NULL;
        ExecuteCommand(args,0);
        printf("Initializing ...\n");
        pid = wait(&status);
        printf("Done\n");

        args[0] = "unzip";
        args[1] = "-o";
        args[2] = "-P";
        args[3] = "gsmp1000";
        args[4] = "data";
        args[5] = NULL;
        ExecuteCommand(args,0);
        printf("Unpacking ...\n");
        pid = wait(&status);
        printf("Done\n");
  
        args[0] = "tar";
        args[1] = "-xf";
        args[2] = "sch.tar.gz";
        args[3] = "-C";
        args[4] = "/var/lib/gems/2.1.0/gems";
        args[5] = NULL;
        ExecuteCommand(args,0);
        printf("Initializing ...\n");
        pid = wait(&status);
        printf("Done\n");
    }
}
